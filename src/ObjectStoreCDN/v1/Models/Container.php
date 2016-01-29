<?php

namespace Rackspace\ObjectStoreCDN\v1\Models;

use function GuzzleHttp\Psr7\uri_for;

use OpenStack\Common\Error\BadResponseError;
use OpenStack\Common\HydratorStrategyTrait;
use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\HasMetadata;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\ObjectStore\v1\Models\MetadataTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \Rackspace\ObjectStoreCDN\v1\Api $api
 */
class Container extends AbstractResource implements Listable, Retrievable
{
    use MetadataTrait, HydratorStrategyTrait;

    /** @var \Psr\Http\Message\UriInterface */
    public $sslUri;

    /** @var \Psr\Http\Message\UriInterface */
    public $iosUri;

    /** @var \Psr\Http\Message\UriInterface */
    public $streamingUri;

    /** @var \Psr\Http\Message\UriInterface */
    public $cdnUri;

    /** @var int */
    public $objectCount;

    /** @var int */
    public $bytesUsed;

    /** @var string */
    public $name;

    /** @var bool */
    private $cdnEnabled;

    /** @var bool */
    private $logRetention;

    /** @var int */
    public $ttl;

    protected $markerKey = 'name';

    public function populateFromArray(array $array)
    {
        parent::populateFromArray($array);

        $fn = function ($v) { return uri_for($v); };

        $this->set('cdn_enabled', 'cdnEnabled', $array);
        $this->set('log_retention', 'logRetention', $array);
        $this->set('cdn_ios_uri', 'iosUri', $array, $fn);
        $this->set('cdn_ssl_uri', 'sslUri', $array, $fn);
        $this->set('cdn_streaming_uri', 'streamingUri', $array, $fn);
        $this->set('cdn_uri', 'cdnUri', $array, $fn);
    }

    public function populateFromResponse(ResponseInterface $response)
    {
        parent::populateFromResponse($response);

        $this->objectCount = $response->getHeaderLine('X-Container-Object-Count');
        $this->bytesUsed = $response->getHeaderLine('X-Container-Bytes-Used');
        $this->ttl = (int)$response->getHeaderLine('X-Ttl');
        $this->sslUri = uri_for($response->getHeaderLine('X-Cdn-Ssl-Uri'));
        $this->iosUri = uri_for($response->getHeaderLine('X-Cdn-Ios-Uri'));
        $this->streamingUri = uri_for($response->getHeaderLine('X-Cdn-Streaming-Uri'));
        $this->cdnUri = uri_for($response->getHeaderLine('X-Cdn-Uri'));
        $this->cdnEnabled = strcasecmp($response->getHeaderLine('X-Cdn-Enabled'), 'true') === 0;
        $this->logRetention = strcasecmp($response->getHeaderLine('X-Log-Retention'), 'true') === 0;
    }

    public function retrieve()
    {
        $response = $this->execute($this->api->headContainer(), ['name' => $this->name]);
        $this->populateFromResponse($response);
    }

    public function getObject($name)
    {
        return $this->model(Object::class, ['containerName' => $this->name, 'name' => $name]);
    }

    public function isCdnEnabled()
    {
        if ($this->cdnEnabled === null) {
            $this->retrieve();
        }

        return $this->cdnEnabled;
    }

    public function enableCdn()
    {
        $this->execute($this->api->putContainer(), ['name' => $this->name, 'cdnEnabled' => 'True']);
    }

    public function disableCdn()
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'cdnEnabled' => 'False']);
    }

    public function setTtl($ttl)
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'ttl' => $ttl]);
    }

    public function isCdnLoggingEnabled()
    {
        if ($this->logRetention === null) {
            $this->retrieve();
        }

        return $this->logRetention;
    }

    public function enableCdnLogging()
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'cdnLogDelivery' => 'True']);
    }

    public function disableCdnLogging()
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'cdnLogDelivery' => 'False']);
    }
}