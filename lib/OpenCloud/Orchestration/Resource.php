<?php
/**
 * Defines an OpenStack Heat Stack
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Stephen Sugden <openstack@stephensugden.com>
 */

namespace OpenCloud\Orchestration;

use OpenCloud\AbstractClass\PersistentObject;

class Resource extends PersistentObject 
{
    protected static $url_resource = 'resources';
    protected static $json_name = 'resource';

    protected $links;
    protected $logical_resource_id;
    protected $physical_resource_id;
    protected $resource_status;
    protected $resource_status_reason;
    protected $resource_type;
    protected $updated_time;

    public function create($info = null) 
    {
        $this->noCreate();
    }

    public function id() 
    {
        return $this->physical_resource_id;
    }

    protected function primaryKeyField() 
    {
        return 'physical_resource_id';
    }

    public function name() 
    {
        return $this->logical_resource_id;
    }

    public function type() 
    {
        return $this->resource_type;
    }

    public function status() 
    {
        return $this->resource_status;
    }

    public function get() 
    {
        $service = $this->parent()->service();
 
        switch ($this->resource_type) {
            case 'AWS::EC2::Instance':
                $objSvc = 'Compute';
                $method = 'Server';
                $name = 'nova';
                break;
            default:
                throw new Exception(sprintf('Unknown resource type %s', 
                    $this->resource_type
                ));
        }
        
        return $service->connection()->$objSvc($name, $service->Region())->$method($this->Id());
    }
}
