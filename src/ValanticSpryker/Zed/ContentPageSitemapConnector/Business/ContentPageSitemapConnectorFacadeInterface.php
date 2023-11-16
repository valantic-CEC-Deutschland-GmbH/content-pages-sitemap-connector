<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business;

interface ContentPageSitemapConnectorFacadeInterface
{
    /**
     *  Specification:
     *  - Creates sitemap XML to be consumed by parent module.
     *
     * @return array
     */
    public function createSitemapXml(): array;
}
