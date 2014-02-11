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
 * The VolumeType class represents a single block storage volume type
 */
class VolumeType extends PersistentObject
{

    public $id;
    public $name;
    public $extra_specs;

    protected static $json_name = 'volume_type';
    protected static $url_resource = 'types';

    /**
     * Creates are not permitted
     *
     * @throws OpenCloud\CreateError always
     */
    public function Create($params = array())
    {
        throw new Exceptions\CreateError(
            Lang::translate('VolumeType cannot be created')
        );
    }

    /**
     * updates are not permitted
     *
     * @throws OpenCloud\UpdateError always
     */
    public function Update($params = array())
    {
        throw new Exceptions\UpdateError(
            Lang::translate('VolumeType cannot be updated')
        );
    }

    /**
     * deletes are not permitted
     *
     * @throws OpenCloud\DeleteError
     */
    public function Delete()
    {
        throw new Exceptions\DeleteError(
            Lang::translate('VolumeType cannot be deleted')
        );
    }
}
