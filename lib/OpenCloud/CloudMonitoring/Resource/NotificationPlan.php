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

class NotificationPlan extends AbstractResource
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string Friendly name for the notification plan.
     */
    private $label;

    /**
     * @var array The notification list to send to when the state is CRITICAL.
     */
    private $critical_state;

    /**
     * @var array The notification list to send to when the state is OK.
     */
    private $ok_state;

    /**
     * @var array The notification list to send to when the state is WARNING.
     */
    private $warning_state;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notification_plans';

    protected static $requiredKeys = array(
        'label'
    );

    protected static $emptyObject = array(
        'label',
        'critical_state',
        'ok_state',
        'warning_state'
    );
}
