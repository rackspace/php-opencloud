<?php declare(strict_types=1);

namespace Rackspace\Identity\v2;

class Api extends \OpenStack\Identity\v2\Api
{
    protected $params;

    public function __construct()
    {
        $this->params = new Params();
    }

    public function postToken(): array 
    {
        return [
            'method' => 'POST',
            'path'   => 'tokens',
            'params' => [
                'username' => $this->params->username(),
                'apiKey'   => $this->params->apiKey(),
            ],
        ];
    }
}