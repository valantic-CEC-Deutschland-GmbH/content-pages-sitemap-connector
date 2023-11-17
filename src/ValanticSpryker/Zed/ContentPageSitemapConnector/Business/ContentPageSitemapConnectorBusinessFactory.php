<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use ValanticSpryker\Service\Sitemap\SitemapServiceInterface;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator\ContentPageDatabaseSitemapCreator;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator\ContentPageSitemapCreatorInterface;
use ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig getConfig()
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\ContentPageSitemapConnectorRepositoryInterface getRepository()
 */
class ContentPageSitemapConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator\ContentPageSitemapCreatorInterface
     */
    public function createProductSitemapCreator(): ContentPageSitemapCreatorInterface
    {
        return new ContentPageDatabaseSitemapCreator(
            $this->getSitemapService(),
            $this->getStoreFacade(),
            $this->getConfig(),
            $this->getRepository(),
        );
    }

    /**
     * @return \ValanticSpryker\Service\Sitemap\SitemapServiceInterface
     */
    private function getSitemapService(): SitemapServiceInterface
    {
        return $this->getProvidedDependency(ContentPageSitemapConnectorDependencyProvider::SERVICE_SITEMAP);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    private function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(ContentPageSitemapConnectorDependencyProvider::FACADE_STORE);
    }
}
