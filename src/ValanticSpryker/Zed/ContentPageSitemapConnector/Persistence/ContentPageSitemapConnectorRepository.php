<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Orm\Zed\UrlStorage\Persistence\Map\SpyUrlStorageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\ContentPageSitemapConnectorPersistenceFactory getFactory()
 */
class ContentPageSitemapConnectorRepository extends AbstractRepository implements ContentPageSitemapConnectorRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStore
     * @param int $page
     * @param int $pageLimit
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer>
     */
    public function findActiveContentPages(StoreTransfer $currentStore, int $page, int $pageLimit): array
    {
        $query = $this->getFactory()
            ->createSpyUrlQuery()
            ->filterByFkResourcePage(null, Criteria::ISNOTNULL)
            ->filterByFkResourceRedirect(null, Criteria::ISNULL)
            ->useSpyLocaleQuery()
                ->useLocaleStoreQuery()
                    ->filterByFkStore($currentStore->getIdStore())
                ->endUse()
            ->endUse()
            ->addJoin(SpyUrlTableMap::COL_ID_URL, SpyUrlStorageTableMap::COL_FK_URL, Criteria::LEFT_JOIN)
            ->withColumn(SpyUrlStorageTableMap::COL_UPDATED_AT, 'updated_at')
            ->setOffset($this->calculateOffsetByPage($page, $pageLimit))
            ->setLimit($pageLimit);

        return $this->getFactory()
            ->createContentPageSitemapMapper()
            ->mapUrlEntitiesToSitemapUrlNodeTransfers(
                $query->find(),
            );
    }

    /**
     * @param int $page
     * @param int $pageLimit
     *
     * @return int
     */
    protected function calculateOffsetByPage(int $page, int $pageLimit): int
    {
        return ($page - 1) * $pageLimit;
    }
}
