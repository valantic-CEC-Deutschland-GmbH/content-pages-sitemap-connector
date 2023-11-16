<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence;

use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\Mapper\ContentPageSitemapConnectorMapper;
use ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\Mapper\ContentPageSitemapConnectorMapperInterface;

/**
 * @method \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig getConfig()
 */
class ContentPageSitemapConnectorPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function createSpyUrlQuery(): SpyUrlQuery
    {
        return SpyUrlQuery::create();
    }

    /**
     * @return \ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\Mapper\ContentPageSitemapConnectorMapperInterface
     */
    public function createContentPageSitemapMapper(): ContentPageSitemapConnectorMapperInterface
    {
        return new ContentPageSitemapConnectorMapper(
            $this->getConfig(),
        );
    }
}
