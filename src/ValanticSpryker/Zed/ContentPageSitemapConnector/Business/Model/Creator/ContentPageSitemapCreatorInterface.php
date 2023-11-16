<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator;

interface ContentPageSitemapCreatorInterface
{
    /**
     * @return array
     */
    public function createContentPagesSitemapXml(): array;
}
