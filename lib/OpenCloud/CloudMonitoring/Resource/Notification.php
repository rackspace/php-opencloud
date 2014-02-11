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

use OpenCloud\Common\Http\Message\Formatter;

/**
 * Notification class.
 */
class Notification extends AbstractResource
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string Friendly name for the notification.
     */
    private $label;

    /**
     * @var string|NotificationType The notification type to send.
     */
    private $type;

    /**
     * @var array A hash of notification specific details based on the notification type.
     */
    private $details;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notifications';

    protected static $emptyObject = array(
        'label',
        'type',
        'details'
    );

    protected static $requiredKeys = array(
        'type',
        'details'
    );

    protected $associatedResources = array(
        'NotificationType' => 'NotificationType'
    );

    public function testUrl($debug = false)
    {
        return $this->getService()->getUrl('test-notification');
    }

    public function test($debug = false)
    {
        $response = $this->getService()
            ->getClient()
            ->post($this->testUrl($debug))
            ->send();

        return Formatter::decode($response);
    }
}
