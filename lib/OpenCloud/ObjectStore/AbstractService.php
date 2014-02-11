<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\ObjectStore;

use OpenCloud\Common\Service\CatalogService;

/**
 * An abstract base class for common code shared between ObjectStore\Service
 * (container) and ObjectStore\CDNService (CDN containers).
 */
abstract class AbstractService extends CatalogService
{
    const MAX_CONTAINER_NAME_LENGTH = 256;
    const MAX_OBJECT_NAME_LEN = 1024;
    const MAX_OBJECT_SIZE = 5102410241025;

    /**
     * List all available containers. If called by a CDN service, it returns CDN-enabled; if called by a regular
     * service, normal containers are returned.
     *
     * @param array $filter
     * @return Collection
     */
    public function listContainers(array $filter = array())
    {
        $filter['format'] = 'json';

        $class = ($this instanceof Service) ? 'Container' : 'CDNContainer';

        return $this->resourceList($class, $this->getUrl(null, $filter), $this);
    }

    /**
     * @return Resource\Account
     */
    public function getAccount()
    {
        return new Resource\Account($this);
    }
}
