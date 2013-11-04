<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\CloudMonitoring\Resource;

use OpenCloud\CloudMonitoring\Exception;

/**
 * Zone class.
 */
class Zone extends ReadOnlyResource
{
    private $id;
	private $country_code;
	private $label;
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
            'target' => $options['target'],
            'target_resolver' => $options['target_resolver']
        );
        try {
        $data = $this->getService()
            ->getClient()
            ->post($this->url('traceroute'), array(), json_encode($params))
            ->send()
            ->getDecodedBody();
        } catch (\Guzzle\Http\Exception\ClientErrorResponseException $e) {
            var_dump((string)$e->getResponse()->getBody());die;
        }
        return (isset($data->result)) ? $data->result : false;
    }
    
}