<?php declare(strict_types=1);

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;

/**
 * Represents a CertMapping resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class CertMapping extends OperatorResource implements Creatable, Updateable, Deletable, Retrievable
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $hostName;

    protected $resourceKey = 'certificateMapping';

    protected $resourcesKey = 'certificateMappings';

    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postCertMapping(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putCertMapping());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteCertMapping());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getCertMapping());
        $this->populateFromResponse($response);
    }
}
