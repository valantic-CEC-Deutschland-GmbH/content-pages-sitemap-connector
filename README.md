# Content Pages Sitemap Connector

# Description

This module is used alongside `valantic-spryker/sitemap` to add additional sitemap resources.

# Usage

1. `composer require valantic-spryker/content-pages-sitemap-connector`
2. Since this is under ValanticSpryker namespace, make sure that in config_default:
   3. `$config[KernelConstants::CORE_NAMESPACES]` has the namespace
   4. `$config[KernelConstants::PROJECT_NAMESPACES]` has the namespace
5. Add `ContentPageSitemapCreatorPlugin` to `\ValanticSpryker\Zed\Sitemap\SitemapDependencyProvider::getSitemapCreatorPluginStack`
6. Now the Sitemap will include **published** content pages.


