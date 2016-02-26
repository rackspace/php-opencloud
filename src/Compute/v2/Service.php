<?php

namespace Rackspace\Compute\v2;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Compute\v2\Models\Image;
use Rackspace\Compute\v2\Models\Keypair;
use Rackspace\Compute\v2\Models\Network;
use Rackspace\Compute\v2\Models\Server;
use Rackspace\Compute\v2\Models\Flavor;

/**
 * @property \Rackspace\Compute\v2\Api $api
 */
class Service extends AbstractResource
{
    public function createServer(array $options)
    {
        return $this->model(Server::class)->create($options);
    }

    public function listServers($detail = false, array $options = [])
    {
        $op = $detail ? $this->api->getServersDetail() : $this->api->getServers();
        return $this->model(Server::class)->enumerate($op, $options);
    }

    public function getServer($id)
    {
        return $this->model(Server::class, ['id' => $id]);
    }

    public function createKeypair(array $options)
    {
        return $this->model(Keypair::class)->create($options);
    }

    public function listKeypairs(array $options = [])
    {
        return $this->model(Keypair::class)->enumerate($this->api->getOskeypairs(), $options);
    }

    public function getKeypair($name)
    {
        return $this->model(Keypair::class, ['name' => $name]);
    }

    public function listFlavors($detail = false, array $options = [])
    {
        $op = $detail ? $this->api->getFlavorsDetail() : $this->api->getFlavors();
        return $this->model(Flavor::class)->enumerate($op, $options);
    }

    public function getFlavor($id)
    {
        return $this->model(Flavor::class, ['id' => $id]);
    }

    public function listImages($detail = false, array $options = [])
    {
        $op = $detail ? $this->api->getImagesDetail() : $this->api->getImages();
        return $this->model(Image::class)->enumerate($op, $options);
    }

    public function getImage($id)
    {
        return $this->model(Image::class, ['id' => $id]);
    }

    public function listNetworks(array $options = [])
    {
        return $this->model(Network::class)->enumerate($this->api->getOsnetworksv2(), $options);
    }

    public function createNetwork(array $options)
    {
        return $this->model(Network::class)->create($options);
    }

    public function getNetwork($id)
    {
        return $this->model(Network::class, ['id' => $id]);
    }
}