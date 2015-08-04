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

namespace OpenCloud\Database\Resource;

use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Resource\PersistentResource;
use OpenCloud\Database\Service;

/**
 * This class represents a Backup
 */
class Backup extends PersistentResource
{
    public $id;
    public $name;
    public $description;
    public $created;
    public $datastore;
    public $updated;
    public $instance;
    public $instanceId;
    public $locationRef;

    protected static $json_name = 'backup';
    protected static $url_resource = 'backups';

    protected $createKeys = array(
        'name',
        'instanceId',
        'description'
    );

    protected $associatedResources = array(
        'instance' => 'Instance'
    );

    protected $aliases = array(
        'instance_id' => 'instanceId'
    );

    public function __construct(Service $service, $info = null)
    {
        $this->instance = new \stdClass;
        return parent::__construct($service, $info);
    }

    /**
     * Returns the JSON object for creating the backup
     */
    protected function createJson()
    {
        if (!isset($this->instanceId)) {
            throw new Exceptions\BackupInstanceError(
                Lang::translate('The `instanceId` attribute is required and must be a string')
            );
        }

        if (!isset($this->name)) {
            throw new Exceptions\BackupNameError(
                Lang::translate('Backup name is required')
            );
        }

        $out = [
            'backup' => [
                  'name'        => $this->name,
                  'instance'    => $this->instanceId
            ]
        ];

        if (isset($this->description)) {
            $out['backup']['description'] = $this->description;
        }
        return (object) $out;
    }
}
