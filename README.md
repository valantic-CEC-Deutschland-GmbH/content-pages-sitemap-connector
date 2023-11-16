# Content Pages Sitemap Connector

# Description

This module is used alongside `valantic-spryker/sitemap` Sitemap module to extend the sitemap with content page (CMS) URls.

# Usage

1. `composer require valantic-spryker/content-pages-sitemap-connector`
2. Since this is under ValanticSpryker namespace, make sure that in config_default:
   1. `$config[KernelConstants::CORE_NAMESPACES]` has the namespace
   2. `$config[KernelConstants::PROJECT_NAMESPACES]` has the namespace
5. Add `ContentPageSitemapCreatorPlugin` to `\ValanticSpryker\Zed\Sitemap\SitemapDependencyProvider::getSitemapCreatorPluginStack`
6. Now the Sitemap will include **published** URLs of content pages.


