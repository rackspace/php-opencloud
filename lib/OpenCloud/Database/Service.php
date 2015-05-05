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

namespace OpenCloud\Database;

use Guzzle\Http\ClientInterface;
use OpenCloud\Common\Service\NovaService;
use OpenCloud\Database\Resource\Instance;
use OpenCloud\Database\Resource\Configuration;
use OpenCloud\Database\Resource\Datastore;
use OpenCloud\Database\Resource\Backup;

/**
 * The Rackspace Database service
 */
class Service extends NovaService
{
    const DEFAULT_TYPE = 'rax:database';
    const DEFAULT_NAME = 'cloudDatabases';

    /**
     * Returns an Instance
     *
     * @param string $id the ID of the instance to retrieve
     * @return \OpenCloud\Database\Resource\Instance
     */
    public function instance($id = null)
    {
        return $this->resource('Instance', $id);
    }

    /**
     * Returns a Collection of Instance objects
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function instanceList($params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Instance::resourceName())->setQuery($params);

        return $this->resourceList('Instance', $url);
    }

    /**
     * Returns a Configuration
     *
     * @param string $id the ID of the configuration to retrieve
     * @return \OpenCloud\Database\Resource\Configuration
     */
    public function configuration($id = null)
    {
        return $this->resource('Configuration', $id);
    }

    /**
     * Returns a Collection of Configuration objects
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function configurationList($params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Configuration::resourceName())->setQuery($params);

        return $this->resourceList('Configuration', $url);
    }

    /**
     * Returns a Datastore
     *
     * @param string $id the ID of the datastore to retrieve
     * @return \OpenCloud\Database\Resource\Datastore
     */
    public function datastore($id = null)
    {
        return $this->resource('Datastore', $id);
    }

    /**
     * Returns a Collection of Datastore objects
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function datastoreList($params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Datastore::resourceName())->setQuery($params);

        return $this->resourceList('Datastore', $url);
    }

    /**
     * Returns a Backup
     *
     * @param string $id the ID of the backup to retrieve
     * @return \OpenCloud\Database\Resource\Backup
     */
    public function backup($id = null)
    {
        return $this->resource('Backup', $id);
    }

    /**
     * Returns a Collection of Backup objects
     *
     * @param array $params
     * @return \OpenCloud\Common\Collection\PaginatedIterator
     */
    public function backupList($params = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Backup::resourceName())->setQuery($params);

        return $this->resourceList('Backup', $url);
    }
}
