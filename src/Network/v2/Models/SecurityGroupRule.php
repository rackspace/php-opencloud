<?php declare(strict_types=1);

namespace Rackspace\Network\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a SecurityGroupRule resource in the Network v2 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class SecurityGroupRule extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $direction;

    /**
     * @var string
     */
    public $ethertype;

    /**
     * @var string
     */
    public $id;

    /**
     * @var integer
     */
    public $portRangeMax;

    /**
     * @var integer
     */
    public $portRangeMin;

    /**
     * @var string
     */
    public $protocol;

    /**
     * @var string
     */
    public $remoteGroupId;

    /**
     * @var string
     */
    public $remoteIpPrefix;

    /**
     * @var string
     */
    public $securityGroupId;

    /**
     * @var string
     */
    public $tenantId;

    protected $aliases = [
        'port_range_max'    => 'portRangeMax',
        'port_range_min'    => 'portRangeMin',
        'remote_group_id'   => 'remoteGroupId',
        'remote_ip_prefix'  => 'remoteIpPrefix',
        'security_group_id' => 'securityGroupId',
        'tenant_id'         => 'tenantId',
    ];

    protected $resourceKey = 'security_group_rule';

    protected $resourcesKey = 'security_group_rules';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postSecuritygrouprules(), $userOptions);
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteRulessecuritygroupsid());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getRulessecuritygroupsid());
        $this->populateFromResponse($response);
    }
}
