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
 * NotificationHistory class.
 */
class NotificationHistory extends ReadOnlyResource
{
    private $id;
    private $timestamp;
    private $notification_plan_id;
    private $transaction_id;
    private $status;
    private $state;
    private $notification_results;
    private $previous_state;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'notification_history';

    public function listChecks()
    {
        $response = $this->getClient()->get($this->url())->send();

        return Formatter::decode($response);
    }

    public function listHistory($checkId)
    {
        return $this->getService()->collection(get_class(), $this->url($checkId));
    }

    public function getSingleHistoryItem($checkId, $historyId)
    {
        $url = $this->url($checkId . '/' . $historyId);
        $response = $this->getClient()->get($url)->send();

        if (null !== ($decoded = Formatter::decode($response))) {
            $this->populate($decoded);
        }

        return false;
    }
}
