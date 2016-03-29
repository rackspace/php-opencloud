<?php declare(strict_types=1);

namespace Rackspace\CDN\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a Flavor resource in the CDN v1 service
 *
 * @property \Rackspace\CDN\v1\Api $api
 */
class Flavor extends AbstractResource implements Listable, Retrievable
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $providers;

    /**
     * @var array
     */
    public $links;

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getFlavor());
        $this->populateFromResponse($response);
    }
}
