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

namespace OpenCloud\Compute\Resource;

use OpenCloud\Common\PersistentObject;
use OpenCloud\Image\Resource\ImageInterface;

/**
 * A collection of files for a specific operating system (OS) that you use to
 * create or rebuild a server. Rackspace provides pre-built images. You can also
 * create custom images from servers that you have launched. Custom images can
 * be used for data backups or as "gold" images for additional servers.
 *
 * @note In the future, this may be abstracted to access Glance (the OpenStack
 * image store) directly, but it is currently not available to Rackspace
 * customers, so we're using the /images resource on the servers API endpoint.
 */

class Image extends PersistentObject implements ImageInterface
{
    public $status;
    public $updated;
    public $links;
    public $minDisk;
    public $id;
    public $name;
    public $created;
    public $progress;
    public $minRam;
    public $metadata;
    public $server;

    protected static $json_name = 'image';
    protected static $url_resource = 'images';

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function create($params = array())
    {
        return $this->noCreate();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }
}
