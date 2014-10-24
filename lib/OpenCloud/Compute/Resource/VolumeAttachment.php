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

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\PersistentObject;

/**
 * The VolumeAttachment class represents a volume that is attached to a server.
 */
class VolumeAttachment extends PersistentObject
{
    public $id;
    public $device;
    public $serverId;
    public $volumeId;

    public static $json_name = 'volumeAttachment';
    public static $url_resource = 'os-volume_attachments';

    private $createKeys = array('volumeId', 'device');

    /**
     * updates are not permitted
     *
     * @throws OpenCloud\UpdateError always
     */
    public function update($params = array())
    {
        throw new Exceptions\UpdateError(Lang::translate('Updates are not permitted'));
    }

    /**
     * returns a readable name for the attachment
     *
     * Since there is no 'name' attribute, we'll hardcode something
     *
     * @api
     * @return string
     */
    public function name()
    {
        return sprintf('Attachment [%s]', $this->volumeId ? : 'N/A');
    }

    /**
     * returns the JSON object for Create()
     *
     * @return stdClass
     */
    protected function createJson()
    {
        $object = new \stdClass;

        foreach ($this->createKeys as $key) {
            if (isset($this->$key)) {
                $object->$key = $this->$key;
            }
        }

        return (object) array(
            $this->jsonName() => $object
        );
    }
}
