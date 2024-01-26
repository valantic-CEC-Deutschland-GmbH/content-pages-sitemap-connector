<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Persistence\Mapper;

use Generated\Shared\Transfer\SitemapUrlNodeTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
use Propel\Runtime\Collection\ObjectCollection;
use ValanticSpryker\Shared\ContentPageSitemapConnector\ContentPageSitemapConnectorConstants;
use ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig;

class ContentPageSitemapConnectorMapper implements ContentPageSitemapConnectorMapperInterface
{
    /**
     * @var string
     */
    private const URL_FORMAT = '%s%s';

    /**
     * @var \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig
     */
    private ContentPageSitemapConnectorConfig $config;

    /**
     * @param \ValanticSpryker\Zed\ContentPageSitemapConnector\ContentPageSitemapConnectorConfig $config
     */
    public function __construct(ContentPageSitemapConnectorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $urlEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlNodeTransfer>
     */
    public function mapUrlEntitiesToSitemapUrlNodeTransfers(ObjectCollection $urlEntities): array
    {
        $transfers = [];

        /** @var \Orm\Zed\Url\Persistence\SpyUrl $urlEntity */
        foreach ($urlEntities as $urlEntity) {
            $transfers[] = $this->createSitemapUrlNodeTransfer($urlEntity);
        }

        return $transfers;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return \Generated\Shared\Transfer\SitemapUrlNodeTransfer
     */
    private function createSitemapUrlNodeTransfer(SpyUrl $urlEntity): SitemapUrlNodeTransfer
    {
        return (new SitemapUrlNodeTransfer())
            ->setUrl($this->formatUrl($urlEntity))
            ->setUpdatedAt($urlEntity->getVirtualColumn('updated_at'))
            ->setResourceId($urlEntity->getFkResourcePage())
            ->setResourceType(ContentPageSitemapConnectorConstants::RESOURCE_TYPE);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     *
     * @return string
     */
    private function formatUrl(SpyUrl $urlEntity): string
    {
        return sprintf(
            self::URL_FORMAT,
            $this->config->getYvesBaseUrl(),
            $urlEntity->getUrl(),
        );
    }
}
