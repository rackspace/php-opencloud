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

namespace OpenCloud\Volume\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\PersistentObject;

/**
 * The Volume class represents a single block storage volume
 */
class Volume extends PersistentObject
{

    public $id;
    public $status;
    public $display_name;
    public $display_description;
    public $size;
    public $volume_type;
    public $metadata = array();
    public $availability_zone;
    public $snapshot_id;
    public $attachments = array();
    public $created_at;

    protected static $json_name = 'volume';
    protected static $url_resource = 'volumes';

    protected $createKeys = array(
        'snapshot_id',
        'display_name',
        'display_description',
        'size',
        'volume_type',
        'availability_zone'
    );

    // Normally we'd populate a sibling object when this one refreshes
    // but there are times (i.e. during creation) when the NAME of the VolumeType
    // is returned, instead of its primary key...
    protected $associatedResources = array(//'volume_type' => 'VolumeType'
    );

    public function update($params = array())
    {
        throw new Exceptions\UpdateError(
            Lang::translate('Block storage volumes cannot be updated')
        );
    }

    public function name()
    {
        return $this->display_name;
    }

    protected function createJson()
    {
        $element = parent::createJson();

        if ($this->propertyExists('volume_type')
            && $this->getProperty('volume_type') instanceof VolumeType
        ) {
            $element->volume->volume_type = $this->volume_type->name();
        }

        return $element;
    }
}
