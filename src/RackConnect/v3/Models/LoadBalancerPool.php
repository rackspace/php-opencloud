<?php

namespace Rackspace\RackConnect\v3\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a LoadBalancerPool resource in the RackConnect v3 service
 *
 * @property \Rackspace\RackConnect\v3\Api $api
 */
class LoadBalancerPool extends AbstractResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var object
     */
    public $nodeCounts;

    /**
     * @var integer
     */
    public $port;

    /**
     * @var string
     */
    public $status;

    /**
     * @var NULL
     */
    public $statusDetail;

    /**
     * @var string
     */
    public $virtualIp;

    protected $aliases = [
        'node_counts'   => 'nodeCounts',
        'status_detail' => 'statusDetail',
        'virtual_ip'    => 'virtualIp',
    ];

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getLoadBalancerPool());
        return $this->populateFromResponse($response);
    }
}