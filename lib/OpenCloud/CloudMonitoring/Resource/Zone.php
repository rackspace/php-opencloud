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

use Guzzle\Http\Exception\ClientErrorResponseException;
use OpenCloud\CloudMonitoring\Exception;
use OpenCloud\Common\Http\Message\Formatter;

/**
 * Zone class.
 */
class Zone extends ReadOnlyResource
{
    /** @var string */
    private $id;

    /** @var string Country Code */
    private $country_code;

    /** @var string */
    private $label;

    /** @var array List of source IPs */
    private $source_ips;

    protected static $json_name = false;
    protected static $json_collection_name = 'values';
    protected static $url_resource = 'monitoring_zones';

    public function traceroute(array $options)
    {
        if (!$this->getId()) {
            throw new Exception\ZoneException(
                'Please specify a zone ID'
            );
        }

        if (!isset($options['target']) || !isset($options['target_resolver'])) {
            throw new Exception\ZoneException(
                'Please specify a "target" and "target_resolver" value'
            );
        }

        $params = (object) array(
            'target'          => $options['target'],
            'target_resolver' => $options['target_resolver']
        );
        try {
            $response = $this->getService()
                ->getClient()
                ->post($this->url('traceroute'), self::getJsonHeader(), json_encode($params))
                ->send();

            $body = Formatter::decode($response);

            return (isset($body->result)) ? $body->result : false;
        } catch (ClientErrorResponseException $e) {
            return false;
        }
    }
}
