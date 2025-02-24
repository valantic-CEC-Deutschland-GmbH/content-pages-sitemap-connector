<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Business\ContentPageSitemapConnectorBusinessFactory getFactory()
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\ContentPageSitemapConnectorRepositoryInterface getRepository()
 */
class ContentPageSitemapConnectorFacade extends AbstractFacade implements ContentPageSitemapConnectorFacadeInterface
{
    /**
     * @inheritDoc
     */
    public function createSitemapXml(string $storeName): array
    {
        return $this->getFactory()
            ->createProductSitemapCreator()
            ->createContentPagesSitemapXml($storeName);
    }
}
