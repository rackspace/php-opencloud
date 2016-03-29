<?php declare(strict_types=1);

namespace Rackspace\ObjectStore\v1\Models;

use OpenStack\ObjectStore\v1\Models\Container as OpenStackContainer;
use Psr\Http\Message\ResponseInterface;

/**
 * @property \Rackspace\ObjectStore\v1\Api $api
 */
class Container extends OpenStackContainer
{
    /** @var int */
    public $quotaBytes;

    /** @var int */
    public $quotaCount;

    public function populateFromResponse(ResponseInterface $response)
    {
        parent::populateFromResponse($response);

        $this->quotaBytes = $response->getHeaderLine('X-Container-Meta-Quota-Bytes');
        $this->quotaCount = $response->getHeaderLine('X-Container-Meta-Quota-Count');
    }

    public function setReadAcl(array $users)
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'readAccess' => implode(', ', $users)]);
    }

    public function setWriteAcl(array $users)
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'writeAccess' => implode(', ', $users)]);
    }

    public function setBytesQuota($total)
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'bytesQuota' => (string) $total]);
    }

    public function setCountQuota($total)
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'countQuota' => (string) $total]);
    }

    public function enableLogging()
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'accessLogDelivery' => 'True']);
    }

    public function disableLogging()
    {
        $this->execute($this->api->postContainer(), ['name' => $this->name, 'accessLogDelivery' => 'False']);
    }
}