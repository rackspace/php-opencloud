<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\HasMetadata;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Transport\Utils;
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
    public $diskConfig;

    /**
     * @var string
     */
    public $id;

    /**
     * @var integer
     */
    public $size;

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
        'OS-DCF:diskConfig'    => 'diskConfig',
        'OS-EXT-IMG-SIZE:size' => 'size',
    ];

    protected $resourceKey = 'image';

    protected $resourcesKey = 'images';

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteImageId());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getImageId());
        return $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata()
    {
        $response = $this->executeWithState($this->api->getMetadata('images'));
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function mergeMetadata(array $metadata)
    {
        $response = $this->execute($this->api->postMetadata('images'), ['id' => $this->id, 'metadata' => $metadata]);
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function resetMetadata(array $metadata)
    {
        foreach (array_diff_key($this->getMetadata(), $metadata) as $removedKey => $v) {
            $this->execute($this->api->deleteMetadataKey('images'), ['id' => $this->id, 'key' => (string) $removedKey]);
        }

        return $this->mergeMetadata($metadata);
    }

    /**
     * {@inheritDoc}
     */
    public function parseMetadata(ResponseInterface $response)
    {
        return Utils::jsonDecode($response)['metadata'];
    }
}