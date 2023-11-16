<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence;

use Generated\Shared\Transfer\StoreTransfer;

interface ContentPageSitemapConnectorRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStore
     * @param int $page
     * @param int $pageLimit
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function findActiveContentPages(StoreTransfer $currentStore, int $page, int $pageLimit): array;
}
