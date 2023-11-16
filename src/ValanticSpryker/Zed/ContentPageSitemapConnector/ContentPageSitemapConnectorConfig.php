<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use ValanticSpryker\Shared\Sitemap\SitemapConstants;

class ContentPageSitemapConnectorConfig extends AbstractBundleConfig
{
    public const RESOURCE_TYPE = 'contentPages';

    public const REDIS_RETRIEVAL = 'redis';

    public const DATABASE_RETRIEVAL = 'database';

    /**
     * @return string
     */
    public function resourceRetrievalMethod(): string
    {
        return self::DATABASE_RETRIEVAL;
    }

    /**
     * @return string
     */
    public function getYvesBaseUrl(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }

    /**
     * @return int
     */
    public function getSitemapUrlLimit(): int
    {
        return $this->get(SitemapConstants::SITEMAP_URL_LIMIT, 100);
    }
}
