<?php

declare(strict_types = 1);

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SitemapFileTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Orm\Zed\UrlStorage\Persistence\Map\SpyUrlStorageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Business\ContentPageSitemapConnectorFacade;
use ValanticSprykerTest\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorTester;

class ContentPageSitemapConnectorFacadeTest extends Unit
{
    public ContentPageSitemapConnectorTester $tester;

    /**
     * @return void
     */
    public function testFacadeRendersCorrectAmountOfUrlsWithCorrectStructure(): void
    {
        // Arrange
        $contentPageConnectorFacade = new ContentPageSitemapConnectorFacade();
        $storeFacade = $this->tester->getLocator()->store()->facade();
        $validEntryCount = SpyUrlQuery::create()
               ->filterByFkResourcePage(null, Criteria::ISNOTNULL)
               ->filterByFkResourceRedirect(null, Criteria::ISNULL)
               ->useSpyLocaleQuery()
                   ->useLocaleStoreQuery()
                       ->filterByFkStore($storeFacade->getCurrentStore()->getIdStore())
                   ->endUse()
               ->endUse()
               ->addJoin(SpyUrlTableMap::COL_ID_URL, SpyUrlStorageTableMap::COL_FK_URL, Criteria::INNER_JOIN)
               ->find()
               ->count();

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

            self::assertNotFalse(parse_url($loc));
            self::assertNotEmpty($lastMod);
        }

        self::assertSame($validEntryCount, $urls->count());
    }
}
