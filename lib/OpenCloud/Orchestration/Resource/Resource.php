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

namespace OpenCloud\Orchestration\Resource;

use OpenCloud\Common\PersistentObject;

/**
 * @codeCoverageIgnore
 */
class Resource extends PersistentObject
{

    protected $links;
    protected $logical_resource_id;
    protected $physical_resource_id;
    protected $resource_status;
    protected $resource_status_reason;
    protected $resource_type;
    protected $resource_metadata;
    protected $updated_time;

    protected static $url_resource = 'resources';
    protected static $json_name = 'resource';
    protected static $json_collection_name = 'resources';

    protected static $resource_type_mapping = array(
        'AWS::EC2::Instance' => array('Compute', 'nova', 'Server'),
    );

    public function create($info = null)
    {
        $this->noCreate();
    }

    public function id()
    {
        return $this->getId();
    }

    public function getId() 
    {
        return $this->physical_resource_id;
    }

    protected function primaryKeyField()
    {
        return 'physical_resource_id';
    }

    public function getName()
    {
        return $this->logical_resource_id;
    }

    public function getType()
    {
        return $this->resource_type;
    }

    public function getStatus()
    {
        return $this->resource_status;
    }

    /**
     * @return object decoded metadata
     */
    public function getMetadata()
    {
        if (!is_null($this->resource_metadata)) {
            return $this->resource_metadata;
        }
        $url = $this->url() . '/metadata';
        $response = $this->getService()->request($url, 'GET', array('Accept' => 'application/json'));

        if ($json = $response->httpBody()) {
            return $this->resource_metadata = @json_decode($json)->metadata;
        } else {
            return array();
        }
    }

    /**
     * Get the object this Stack Resource refers to.
     *
     * @return PersistentObject varies depending on the type of resource being fetched
     */
    public function get()
    {
        if (!isset(static::$resource_type_mapping[$this->resource_type])) {
            throw new \Exception("Unknown resource type {$this->resource_type}");
        }

        $orchService = $this->getService();
        $connection  = $orchService->getConnection();
        $region      = $orchService->region();

        list($serviceType, $serviceName, $method) =
            self::$resource_type_mapping[$this->resource_type];

        $resourceService = $connection->service($serviceType, $serviceName, $region);
 
        return $resourceService->$method($this->getId());
    }
}
