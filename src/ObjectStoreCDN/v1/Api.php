<?php declare(strict_types=1);

namespace Rackspace\ObjectStoreCDN\v1;

class Api extends \Rackspace\ObjectStore\v1\Api
{
    public function __construct()
    {
        $this->params = new Params();
    }

    public function putContainer(): array
    {
        $parent = parent::putContainer();
        $parent['params']['cdnEnabled'] = $this->params->cdnEnabled();
        $parent['params']['ttl'] = $this->params->ttl();
        return $parent;
    }

    public function postContainer(): array 
    {
        $parent = parent::postContainer();
        $parent['params']['cdnLogDelivery'] = $this->params->cdnLogDelivery();
        $parent['params']['cdnEnabled'] = $this->params->cdnEnabled();
        $parent['params']['ttl'] = $this->params->ttl();
        return $parent;
    }
}