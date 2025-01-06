<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator;

interface ContentPageSitemapCreatorInterface
{
    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapFileTransfer>
     */
    public function createContentPagesSitemapXml(string $storeName): array;
}
