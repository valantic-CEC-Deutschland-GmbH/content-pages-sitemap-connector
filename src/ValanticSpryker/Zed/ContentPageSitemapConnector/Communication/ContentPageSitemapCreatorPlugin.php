<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Communication;

use Spryker\Glue\Kernel\Backend\AbstractPlugin;
use ValanticSpryker\Zed\Sitemap\Dependency\Plugin\SitemapCreatorPluginInterface;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Business\ContentPageSitemapConnectorFacadeInterface getFacade()
 */
class ContentPageSitemapCreatorPlugin extends AbstractPlugin implements SitemapCreatorPluginInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createSitemapXml(): array
    {
        return $this->getFacade()
            ->createSitemapXml();
    }
}
