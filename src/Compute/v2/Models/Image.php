<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\HasMetadata;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents a Image resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Image extends AbstractResource implements Listable, Deletable, Retrievable, HasMetadata
{
    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $oSDCFdiskConfig;

    /**
     * @var string
     */
    public $id;

    /**
     * @var integer
     */
    public $oSEXTIMGSIZEsize;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $created;

    /**
     * @var integer
     */
    public $minDisk;

    /**
     * @var object
     */
    public $server;

    /**
     * @var integer
     */
    public $progress;

    /**
     * @var integer
     */
    public $minRam;

    /**
     * @var object
     */
    public $metadata;

    protected $aliases = [
        'OS-DCF:diskConfig'    => 'oSDCFdiskConfig',
        'OS-EXT-IMG-SIZE:size' => 'oSEXTIMGSIZEsize',
    ];

    protected $resourceKey = 'image';

    protected $resourcesKey = 'images';

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteImage());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getImage());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function mergeMetadata(array $metadata)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function resetMetadata(array $metadata)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function parseMetadata(ResponseInterface $response)
    {
    }
}