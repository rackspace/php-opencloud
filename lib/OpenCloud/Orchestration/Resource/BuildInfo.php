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

namespace OpenCloud\Orchestration\Resource;

use OpenCloud\Common\Exceptions;
use OpenCloud\Common\Lang;
use OpenCloud\Common\Resource\PersistentResource;

/**
 * Information about the current Orchestration service build.
 */
class BuildInfo extends PersistentResource
{

    /**
     * Engine information.
     *
     * @var object
     */
    protected $engine;

    /**
     * API information.
     *
     * @var object
     */
    protected $api;

    protected static $json_name = "does_not_exist";
    protected static $url_resource = "build_info";

    public function refresh($id = null, $url = null)
    {
        return parent::refresh(null, $this->getUrl());
    }

    public function create($params = array())
    {
        return $this->noCreate();
    }

    public function update($params = array())
    {
        return $this->noUpdate();
    }

    public function delete($params = array())
    {
        return $this->noDelete();
    }

    public function getEngine()
    {
        return $this->engine;
    }

    public function getApi()
    {
        return $this->api;
    }

}
