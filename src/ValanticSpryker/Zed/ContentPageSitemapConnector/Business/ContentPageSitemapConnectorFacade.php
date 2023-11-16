<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Business\ContentPageSitemapConnectorBusinessFactory getFactory()
 */
class ContentPageSitemapConnectorFacade extends AbstractFacade implements ContentPageSitemapConnectorFacadeInterface
{
    /**
     * @inheritDoc
     */
    public function createSitemapXml(): array
    {
        return $this->getFactory()
            ->createProductSitemapCreator()
            ->createContentPagesSitemapXml();
    }
}
