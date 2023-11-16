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
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
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
           ->addJoin(SpyUrlTableMap::COL_ID_URL, SpyUrlStorageTableMap::COL_FK_URL, Criteria::INNER_JOIN)
           ->withColumn(SpyUrlStorageTableMap::COL_UPDATED_AT, 'updated_at');

        if ($page !== null && $pageLimit !== null) {
            $offset = ($page - 1) * $pageLimit;
            $query
               ->setOffset($offset)
               ->setLimit($pageLimit);
        }

        return $this->getFactory()
            ->createContentPageSitemapMapper()
            ->mapUrlEntitiesToSitemapUrlTransfers(
                $query->find(),
            );
    }
}
