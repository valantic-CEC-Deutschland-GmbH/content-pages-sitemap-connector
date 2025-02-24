<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Communication\Plugin;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Business\ContentPageSitemapConnectorFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig getConfig()
 */
class ContentPageSitemapCreatorPlugin extends AbstractPlugin implements SitemapCreatorPluginInterface
{
    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(string $storeName): array
    {
        return $this->getFacade()
            ->createSitemapXml($storeName);
    }
}
