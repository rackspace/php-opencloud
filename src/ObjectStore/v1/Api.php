<?php declare(strict_types=1);

namespace Rackspace\ObjectStore\v1;

class Api extends \OpenStack\ObjectStore\v1\Api
{
    public function __construct()
    {
        $this->params = new Params();
    }

    public function putAccount(): array
    {
        return [
            'method' => 'PUT',
            'path'   => '',
            'params' => [
                'content'        => $this->params->content(),
                'stream'         => $this->params->stream(),
                'extractArchive' => $this->params->extractArchive(),
            ],
        ];
    }

    public function deleteAccount(): array
    {
        return [
            'method' => 'DELETE',
            'path'   => '?bulk-delete',
            'params' => [
                'content' => $this->params->content(),
            ],
        ];
    }

    public function postContainer(): array
    {
        $parent = parent::postContainer();
        $parent['params']['accessLogDelivery'] = $this->params->accessLogDelivery();
        return $parent;
    }
}