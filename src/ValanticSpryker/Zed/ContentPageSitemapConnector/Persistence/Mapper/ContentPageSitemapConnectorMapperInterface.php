<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\Mapper;

use Propel\Runtime\Collection\ObjectCollection;

interface ContentPageSitemapConnectorMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $urlEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer>
     */
    public function mapUrlEntitiesToSitemapUrlNodeTransfers(ObjectCollection $urlEntities): array;
}
