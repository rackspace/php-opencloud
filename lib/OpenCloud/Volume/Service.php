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

class Service extends NovaService
{
    const DEFAULT_TYPE = 'volume';
    const DEFAULT_NAME = 'cloudBlockStorage';

    /**
     * Returns a Volume object
     *
     * @api
     * @param string $id the Volume ID
     * @return Resource\Volume
     */
    public function volume($id = null)
    {
        return new Resource\Volume($this, $id);
    }

    /**
     * Returns a Collection of Volume objects
     *
     * @api
     * @param boolean $details if TRUE, return all details
     * @param array   $filter  array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function volumeList($details = true, $filter = array())
    {
        $url = clone $this->getUrl(Resource\Volume::ResourceName());
        if ($details) {
            $url->addPath('detail');
        }

        return $this->collection('OpenCloud\Volume\Resource\Volume', $url);
    }

    /**
     * Returns a VolumeType object
     *
     * @api
     * @param string $id the VolumeType ID
     * @return Resource\Volume
     */
    public function volumeType($id = null)
    {
        return new Resource\VolumeType($this, $id);
    }

    /**
     * Returns a Collection of VolumeType objects
     *
     * @api
     * @param array $filter array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function volumeTypeList($filter = array())
    {
        return $this->collection('\OpenCloud\Volume\Resource\VolumeType');
    }

    /**
     * returns a Snapshot object associated with this volume
     *
     * @return Resource\Snapshot
     */
    public function snapshot($id = null)
    {
        return new Resource\Snapshot($this, $id);
    }

    /**
     * Returns a Collection of Snapshot objects
     *
     * @api
     * @param array $filter array of filter key/value pairs
     * @return \OpenCloud\Common\Collection
     */
    public function snapshotList($filter = array())
    {
        return $this->collection('OpenCloud\Volume\Resource\Snapshot');
    }
}
