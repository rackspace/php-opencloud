<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Autoscale\Resource;

/**
 * Description of Webhook
 * 
 * @link 
 */
class Webhook extends AbstractResource
{
    
    public $id;
    public $name;
    public $metadata;
    public $links;
    
    protected static $json_name = 'webhook';
    protected static $url_resource = 'webhooks';
    
    public $createKeys = array(
        'name',
        'metadata'
    );
    
    public function createJson()
    {
        $object = new \stdClass;
        $object->name = $this->name;
        $object->metadata = $this->metadata;
       
        return $object;
    }
    
}