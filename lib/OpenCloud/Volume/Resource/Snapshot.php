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

use OpenCloud\Common\Resource\PersistentResource;

/**
 * The Snapshot class represents a single block storage snapshot
 */
class Snapshot extends PersistentResource
{
    public $id;
    public $display_name;
    public $display_description;
    public $volume_id;
    public $status;
    public $size;
    public $created_at;
    public $metadata;

    protected $force = false;

    protected static $json_name = 'snapshot';
    protected static $url_resource = 'snapshots';

    protected $createKeys = array(
        'display_name',
        'display_description',
        'volume_id',
        'force'
    );

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

        $keys = array('display_description' => true, 'display_name' => true);

        foreach ($params as $key => $value) {
            if (isset($keys[$key])) {
                $data[$key] = $value;
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'You cannot update the %s snapshot property. Valid keys are: %s',
                    $key, implode($keys, ',')
                ));
            }
        }

        $json = json_encode(array(
           'snapshot' => $data
        ));

        return $this->getClient()
            ->put($this->getUrl(), self::getJsonHeader(), $json)
            ->send();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }

    public function name()
    {
        return $this->display_name;
    }
}
