<?php declare(strict_types=1);

namespace Rackspace\Compute\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\HasMetadata;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Transport\Utils;
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
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata(): array
    {
        $response = $this->executeWithState($this->api->getMetadata('images'));
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function mergeMetadata(array $metadata): array
    {
        $response = $this->execute($this->api->postMetadata('images'), ['id' => $this->id, 'metadata' => $metadata]);
        return $this->parseMetadata($response);
    }

    /**
     * {@inheritDoc}
     */
    public function resetMetadata(array $metadata): array
    {
        foreach (array_diff_key($this->getMetadata(), $metadata) as $removedKey => $v) {
            $this->execute($this->api->deleteMetadataKey('images'), ['id' => $this->id, 'key' => (string) $removedKey]);
        }

        return $this->mergeMetadata($metadata);
    }

    /**
     * {@inheritDoc}
     */
    public function parseMetadata(ResponseInterface $response): array
    {
        return Utils::jsonDecode($response)['metadata'];
    }
}