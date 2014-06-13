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

namespace OpenCloud\Autoscale\Resource;

/**
 * Description of ScalingPolicy
 */
class ScalingPolicy extends AbstractResource
{
    public $id;
    public $links;
    public $name;
    public $change;
    public $cooldown;
    public $type;
    public $metadata;

    protected static $json_name = 'policy';
    protected static $json_collection_name = 'policies';
    protected static $url_resource = 'policies';

    public $createKeys = array(
        'name',
        'change',
        'cooldown',
        'type'
    );

    public function getWebhookList()
    {
        return $this->getService()->resourceList('Webhook', null, $this);
    }

    public function getWebhook($id = null)
    {
        return $this->getService()->resource('Webhook', $id, $this);
    }

    public function createWebhooks(array $webhooks)
    {
        $url = clone $this->getUrl();
        $url->addPath('webhooks');

        $body = json_encode($webhooks);
        $this->checkJsonError();

        return $this->getService()
            ->getClient()
            ->post($url, self::getJsonHeader(), $body)
            ->send();
    }

    public function execute()
    {
        return $this->getClient()->post($this->url('execute'))->send();
    }
}
