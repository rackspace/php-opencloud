<?php declare(strict_types=1);

namespace Rackspace\DNS\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a Domain resource in the DNS v1 service
 *
 * @property \Rackspace\DNS\v1\Api $api
 */
class Domain extends OperatorResource implements Creatable, Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var array
     */
    public $nameservers;

    /**
     * @var integer
     */
    public $accountId;

    /**
     * @var object
     */
    public $recordsList;

    /**
     * @var integer
     */
    public $ttl;

    /**
     * @var string
     */
    public $emailAddress;

    /**
     * @var string
     */
    public $created;

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postDomains(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putDomains());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteDomains());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getDomains());
        $this->populateFromResponse($response);
    }
}
