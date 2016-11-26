<?php declare(strict_types=1);

namespace Rackspace\CDN\v1\Models;

use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;

/**
 * Represents a SslCertificate resource in the CDN v1 service
 *
 * @property \Rackspace\CDN\v1\Api $api
 */
class SslCertificate extends OperatorResource implements Creatable, Deletable
{
    /**
     * {@inheritDoc}
     */
    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->postSslCertificate(), $userOptions);
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteSslCertificate());
    }
}
