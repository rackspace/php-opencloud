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

namespace OpenCloud\CloudMonitoring\Resource;

/**
 * View class.
 */
class View extends ReadOnlyResource
{
    private $timestamp;
    private $entity;
    private $alarms;
    private $checks;
    private $latest_alarm_states;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'views/overview';

    protected $associatedResources = array(
        'entity' => 'Entity'
    );

    protected $associatedCollections = array(
        'alarms' => 'Alarm',
        'checks' => 'Check'
    );

    public function getAlarm($info = null)
    {
        return $this->getService()->resource('Alarm', $info);
    }

    public function getCheck($info = null)
    {
        return $this->getService()->resource('Check', $info);
    }
}
