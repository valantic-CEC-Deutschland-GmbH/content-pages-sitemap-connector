<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator;

use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;
use ValanticSpryker\Shared\ContentPageSitemapConnector\ContentPageSitemapConnectorConstants;
use ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\ContentPageSitemapConnectorRepositoryInterface;

class ContentPageDatabaseSitemapCreator implements ContentPageSitemapCreatorInterface
{
    /**
     * @var \ValanticSpryker\Service\Sitemap\SitemapServiceInterface
     */
    private SitemapServiceInterface $sitemapService;

    /**
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    private StoreFacadeInterface $storeFacade;

    /**
     * @var \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig
     */
    private ContentPageSitemapConnectorConfig $config;

    /**
     * @var \ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\ContentPageSitemapConnectorRepositoryInterface
     */
    private ContentPageSitemapConnectorRepositoryInterface $repository;

    /**
     * @param \ValanticSpryker\Service\Sitemap\SitemapServiceInterface $sitemapService
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig $config
     * @param \ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\ContentPageSitemapConnectorRepositoryInterface $repository
     */
    public function __construct(
        SitemapServiceInterface $sitemapService,
        StoreFacadeInterface $storeFacade,
        ContentPageSitemapConnectorConfig $config,
        ContentPageSitemapConnectorRepositoryInterface $repository
    ) {
        $this->sitemapService = $sitemapService;
        $this->storeFacade = $storeFacade;
        $this->config = $config;
        $this->repository = $repository;
    }

    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createContentPagesSitemapXml(string $storeName): array
    {
        $urlLimit = $this->config->getSitemapUrlLimit();
        $sitemapList = [];
        $page = 1;

        $store = $this->storeFacade->findStoreByName($storeName);
        if (!$store) {
            return $sitemapList;
        }

        do {
            $urlList = $this->repository->findActiveContentPages(
                $store,
                $page,
                $urlLimit,
            );

            $sitemapTransfer = $this->sitemapService->createSitemapXmlFileTransfer(
                $urlList,
                $page,
                $storeName,
                ContentPageSitemapConnectorConstants::RESOURCE_TYPE,
            );

            if ($sitemapTransfer !== null) {
                $sitemapList[] = $sitemapTransfer;
            }

            $page++;
        } while ($sitemapTransfer !== null);

        return $sitemapList;
    }
}
