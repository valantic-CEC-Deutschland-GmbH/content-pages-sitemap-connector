<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\ContentPageSitemapConnector\Business\Model\Creator;

class ContentPageRedisSitemapCreator implements ContentPageSitemapCreatorInterface
{
 /**
  * @return array
  */
    public function createContentPagesSitemapXml(): array
    {
        return []; // @todo Implement Redis provider, if necessary use case arises
    }
}
