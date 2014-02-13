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

namespace OpenCloud\Volume;

use OpenCloud\Common\Service\NovaService;
use OpenCloud\Volume\Resource\Snapshot;

class Service extends NovaService
{
    const DEFAULT_TYPE = 'volume';
    const DEFAULT_NAME = 'cloudBlockStorage';

    /**
     * Returns a Volume object
     *
     * @param string $id the Volume ID
     * @return Resource\Volume
     */
    public function volume($id = null)
    {
        return $this->resource('Volume', $id);
    }

    /**
     * Returns a Collection of Volume objects
     *
     * @param boolean $details if TRUE, return all details
     * @param array   $filter  array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function volumeList($details = true, $filter = array())
    {
        $url = clone $this->getUrl(Resource\Volume::ResourceName());

        if ($details === true) {
            $url->addPath('detail');
        }

        $url->setQuery($filter);

        return $this->resourceList('Volume', $url);
    }

    /**
     * Returns a VolumeType object
     *
     * @param string $id the VolumeType ID
     * @return Resource\Volume
     */
    public function volumeType($id = null)
    {
        return $this->resource('VolumeType', $id);
    }

    /**
     * Returns a Collection of VolumeType objects
     *
     * @param array $filter array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function volumeTypeList($filter = array())
    {
        return $this->resourceList('VolumeType');
    }

    /**
     * Returns a Snapshot object associated with this volume
     *
     * @param null $id
     * @return Resource\Snapshot
     */
    public function snapshot($id = null)
    {
        return $this->resource('Snapshot', $id);
    }

    /**
     * Returns a Collection of Snapshot objects
     *
     * @param array $filter array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function snapshotList($filter = array())
    {
        $url = clone $this->getUrl();
        $url->addPath(Snapshot::resourceName())->setQuery($filter);

        return $this->resourceList('Snapshot', $url);
    }
}
