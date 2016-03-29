<?php declare(strict_types=1);

namespace Rackspace\Network\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a SecurityGroup resource in the Network v2 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class SecurityGroup extends AbstractResource implements Creatable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var []SecurityGroupRule
     */
    public $securityGroupRules;

    /**
     * @var string
     */
    public $tenantId;

    protected $aliases = [
        'security_group_rules' => 'securityGroupRules',
        'tenant_id'            => 'tenantId',
    ];

    protected $resourceKey  = 'security_group';
    protected $resourcesKey = 'security_groups';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postSecuritygroups(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteSecurityGroupId());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getSecurityGroupId());
        $this->populateFromResponse($response);
    }
}