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
 * Description of ScalingPolicy
 * 
 * @link 
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
    protected static $json_collection_element = 'data';
    
    public $createKeys = array(
        'name',
        'change',
        'cooldown',
        'type'
    );
    
    public function getWebhookList()
    {
        return $this->service()->resourceList('Webhook', null, $this);
    }
    
    public function getWebhook($id = null)
    {
        $webhook = new Webhook();
        $webhook->setParent($this);
        $webhook->setService($this->service());
        if ($id) {
            $webhook->populate($id);
        }
        return $webhook;
    }
    
    public function webhook($info)
    {
        $webhook = $this->getWebhook();
        $webhook->populate($info);
        return $webhook;
    }
    
    public function execute()
    {
        return $this->customAction($this->url('execute', true), 'POST');
    }
    
}