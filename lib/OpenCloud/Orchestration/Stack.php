<?php
/**
 * Defines an OpenStack Heat Stack
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 * @author Stephen Sugden <openstack@stephensugden.com>
 */

namespace OpenCloud\Orchestration;

use OpenCloud\AbstractClass\PersistentObject;
use OpenCloud\Exceptions\CreateError;

/**
 * The Stack class requires a CloudFormation template and may contain additional
 * parameters for that template.
 *
 * A Stack is always associated with an (Orchestration) Service.
 *
 * @api
 * @author Stephen Sugden <openstack@stephensugden.com>
 */
class Stack extends PersistentObject 
{
    
    protected static $json_name = "stack";
    protected static $url_resource = "stacks";
    protected static $required_properties = array('template', 'stack_name');

    protected $id;
    protected $stack_name;
    protected $parameters;
    protected $template;
    protected $disable_rollback;
    protected $description;
    protected $stack_status_reason;
    protected $outputs;
    protected $creation_time;
    protected $links;
    protected $capabilities;
    protected $notification_topics;
    protected $timeout_mins;
    protected $stack_status;
    protected $updated_time;
    protected $template_description;

    public function __construct(Service $service, $info) 
    {
        parent::__construct($service, $info);
    }

    protected function createJson() 
    {
        $pk = $this->primaryKeyField();
        if (isset($this->{$pk})) {
            throw new CreateError("Stack is already created: {$this->$pk}");
        }

        $obj = new \stdClass();
        $obj->disable_rollback = false;
        $obj->timeout_mins = 60;

        foreach (self::$required_properties as $property) {
            if (is_null($this->{$property})) {
                throw new CreateError(sprintf('Cannot create Stack with null %s', $property));
            }
            else {
                $obj->$property = $this->$property;
            }
        }
        if (!is_null($this->parameters)) {
            $obj->parameters = $this->parameters;
        }
        return $obj;
    }

    public function name() 
    {
        return $this->stack_name;
    }

    public function status() 
    {
        return $this->stack_status;
    }

    public function resource($id = null) 
    {
        $resource = new Resource($this->getService());
        $resource->setParent($this);
        $resource->populate($id);
        return $resource;
    }

    public function resources() 
    {
        $svc = $this->Service();
        $url = $this->Url('resources');
        return $svc->Collection('\OpenCloud\Orchestration\Resource', $url, $this);
    }
}
