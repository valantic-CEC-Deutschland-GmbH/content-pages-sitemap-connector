<?php

declare(strict_types = 1);

namespace ValanticSprykerTest\Zed\ContentPageSitemapConnector\Business;

use ArrayObject;
use Codeception\Test\Unit;
use DOMDocument;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use InvalidArgumentException;
use League\Uri\Uri;
use Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\UrlStorage\Persistence\Map\SpyUrlStorageTableMap;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorageQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Business\ContentPageSitemapConnectorFacade;
use ValanticSprykerTest\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorTester;

/**
 * Auto-generated group annotations
 *
 * @group ValanticSprykerTest
 * @group Zed
 * @group ContentPageSitemapConnector
 * @group Business
 * @group ContentPageSitemapConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ContentPageSitemapConnectorFacadeTest extends Unit
{
    public ContentPageSitemapConnectorTester $tester;

    /**
     *
     * @return void
     */
    public function testFacadeRendersCorrectAmountOfUrlsWithCorrectStructure(): void
    {
        // Arrange
        $contentPageConnectorFacade = new ContentPageSitemapConnectorFacade();
        $storeFacade = $this->tester->getLocator()->store()->facade();
        $idStore = $storeFacade->getCurrentStore()->getIdStore();
        $validEntries = SpyUrlQuery::create()
            ->filterByFkResourcePage(null, Criteria::ISNOTNULL)
            ->filterByFkResourceRedirect(null, Criteria::ISNULL)
            ->useCmsPageQuery()
                ->useSpyCmsPageStoreQuery()
                    ->filterByFkStore($idStore)
                ->endUse()
            ->endUse()
            ->addJoin(SpyUrlTableMap::COL_ID_URL, SpyUrlStorageTableMap::COL_FK_URL, Criteria::INNER_JOIN)
            ->select(SpyUrlTableMap::COL_URL)
            ->find()
            ->getData();

        $validEntries = array_map(static function (string $url): string {
            return Uri::createFromString($url)->toString();
        }, $validEntries);

        $validEntryCount = count($validEntries);

        // Act
        /** @var array<\Generated\Shared\Transfer\SitemapFileTransfer> $result */
        $result = $contentPageConnectorFacade->createSitemapXml()[0] ?? null;

        // Assert
        self::assertInstanceOf(SitemapFileTransfer::class, $result);
        self::assertNotEmpty($result->getContent());
        self::assertNotEmpty($result->getStoreName());
        self::assertNotFalse(parse_url($result->getYvesBaseUrl()));

        $domDoc = new DOMDocument();
        $domDoc->loadXML($result->getContent());
        $urls = $domDoc->getElementsByTagName('url');

        foreach ($urls as $url) {
            $loc = $url->getElementsByTagName('loc')->item(0)->textContent;
            $lastMod = $url->getElementsByTagName('lastmod')->item(0)->textContent;

            self::assertContains(rtrim(parse_url($loc)['path']), $validEntries);

            self::assertNotFalse(parse_url($loc));
            self::assertNotEmpty($lastMod);
        }

        self::assertSame($validEntryCount, $urls->count());
    }

    /**
     * @return void
     */
    public function testOnlyLinksVisibleToStoreAreRendered(): void
    {
        // ARRANGE
        $allStores = $this->tester->getLocator()->store()->facade()->getAllStores();
        $currentStore = $this->tester->getLocator()->store()->facade()->getCurrentStore();
        $otherStore = $this->getDifferentCurrentStore($allStores, $currentStore);

        $locale1 = $this->tester->getLocator()->locale()->facade()->getLocale($currentStore->getDefaultLocaleIsoCode());
        $locale2 = $this->tester->getLocator()->locale()->facade()->getLocale($otherStore->getDefaultLocaleIsoCode());

        $cmsPage1 = $this->tester->haveCmsPage([
            CmsPageTransfer::IS_ACTIVE => 1,
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageAttributesTransfer::FK_LOCALE => $locale1->getIdLocale(),
            CmsPageAttributesTransfer::LOCALE_NAME => $locale1->getLocaleName(),
            CmsPageTransfer::STORE_RELATION => $this->createStoreRelationByStore($currentStore)->toArray(),
        ]);

        $cmsPage2 = $this->tester->haveCmsPage([
            CmsPageTransfer::IS_ACTIVE => 1,
            CmsPageTransfer::FK_TEMPLATE => 1,
            CmsPageAttributesTransfer::FK_LOCALE => $locale2->getIdLocale(),
            CmsPageAttributesTransfer::LOCALE_NAME => $locale2->getLocaleName(),
            CmsPageTransfer::STORE_RELATION => $this->createStoreRelationByStore($otherStore)->toArray(),
        ]);

        $var = SpyCmsPageStoreQuery::create()->filterByFkStore($currentStore->getIdStore())->filterByFkCmsPage($cmsPage1->getFkPage())->find();

        $url = SpyUrlQuery::create()
            ->findOneByFkResourcePage($cmsPage1->getFkPage());

        $url2 = SpyUrlQuery::create()
            ->findOneByFkResourcePage($cmsPage2->getFkPage());

        $this->tester->getLocator()->urlStorage()->facade()->publishUrl(
            [
                $url->getIdUrl(),
                $url2->getIdUrl(),
            ],
        );

        // ACT
        $sitemapXml = $this->tester->getLocator()->contentPageSitemapConnector()->facade()->createSitemapXml();

        // ASSERT
        self::assertTrue($this->containsUrlInSitemap($sitemapXml, Uri::createFromString($url->getUrl())->toString()));
        self::assertFalse($this->containsUrlInSitemap($sitemapXml, Uri::createFromString($url2->getUrl())->toString()));
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $allStores
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStore
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    private function getDifferentCurrentStore(array $allStores, StoreTransfer $currentStore): StoreTransfer
    {
        foreach ($allStores as $store) {
            if ($store->getIdStore() !== $currentStore->getIdStore()) {
                return $store;
            }
        }

        throw new InvalidArgumentException();
    }

    /**
     * @param array<\Generated\Shared\Transfer\SitemapFileTransfer> $sitemapXml
     * @param string $needle
     *
     * @return bool
     */
    private function containsUrlInSitemap(array $sitemapXml, string $needle): bool
    {
        foreach ($sitemapXml as $item) {
            if (strpos($item->getContent(), $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $store
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    private function createStoreRelationByStore(StoreTransfer $store): StoreRelationTransfer
    {
        $stores = new ArrayObject();
        $stores->append($store);

        return (new StoreRelationTransfer())
            ->setStores($stores)
            ->setIdStores([$store->getIdStore()]);
    }
}
