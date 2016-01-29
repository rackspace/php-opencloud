<?php

namespace Rackspace\Identity\v2;

use GuzzleHttp\ClientInterface;

class Service extends \OpenStack\Identity\v2\Service
{
    public static function factory(ClientInterface $client)
    {
        return new static($client, new Api());
    }
}