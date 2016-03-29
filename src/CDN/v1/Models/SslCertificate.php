<?php declare(strict_types=1);

namespace Rackspace\CDN\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;

/**
 * Represents a SslCertificate resource in the CDN v1 service
 *
 * @property \Rackspace\CDN\v1\Api $api
 */
class SslCertificate extends AbstractResource implements Creatable, Deletable
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
