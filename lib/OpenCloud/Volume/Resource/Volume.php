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
use OpenCloud\Common\Resource\PersistentResource;

/**
 * The Volume class represents a single block storage volume
 */
class Volume extends PersistentResource
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
    public $source_volid;
    public $imageRef;
    public $bootable;

    protected static $json_name = 'volume';
    protected static $url_resource = 'volumes';

    protected $createKeys = array(
        'snapshot_id',
        'display_name',
        'display_description',
        'size',
        'volume_type',
        'availability_zone',
        'metadata',
        'source_volid',
        'bootable',
        'imageRef'
    );

    protected $associatedResources = array();

    public function update($params = array())
    {
        throw new Exceptions\UpdateError(
            Lang::translate('Block storage volumes cannot be updated')
        );
    }

    /**
     * Rename either the `display_description` or the `display_name` properties
     *
     * @param array $params
     * @return \Guzzle\Http\Message\Response
     * @throws \InvalidArgumentException
     */
    public function rename(array $params = array())
    {
        $data = array();

        $keys = array('display_description', 'display_name');

        foreach ($params as $key => $value) {
            if (in_array($key, $keys)) {
                $data[$key] = $value;
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'You cannot update the %s volume property. Valid keys are: %s',
                    $key, implode($keys, ',')
                ));
            }
        }

        $json = json_encode(array(
           'volume' => $data
        ));

        return $this->getClient()
            ->put($this->getUrl(), self::getJsonHeader(), $json)
            ->send();
    }

    public function name()
    {
        return $this->display_name;
    }

    protected function createJson()
    {
        $element = parent::createJson();

        if ($this->getProperty('volume_type') instanceof VolumeType) {
            $element->volume->volume_type = $this->volume_type->name();
        }

        return $element;
    }
}
