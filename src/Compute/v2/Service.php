<?php declare(strict_types=1);

namespace Rackspace\Compute\v2;

use OpenStack\Common\Service\AbstractService;
use Rackspace\Compute\v2\Models\Image;
use Rackspace\Compute\v2\Models\Keypair;
use Rackspace\Compute\v2\Models\Network;
use Rackspace\Compute\v2\Models\Server;
use Rackspace\Compute\v2\Models\Flavor;

/**
 * @property \Rackspace\Compute\v2\Api $api
 */
class Service extends AbstractService
{
    /**
     * Creates a server
     *
     * @param array $options {@see \Rackspace\Compute\v2\Api::postServers}
     *
     * @return Server
     */
    public function createServer(array $options): Server
    {
        return $this->model(Server::class)->create($options);
    }

    /**
     * Lists existing servers
     *
     * @param bool  $detail  Indicates whether a detailed list is returned, or only a brief version
     * @param array $options {@see \Rackspace\Compute\v2\Api::getServers}
     *
     * @return \Generator
     */
    public function listServers($detail = false, array $options = []): \Generator
    {
        $op = $detail ? $this->api->getServersDetail() : $this->api->getServers();
        return $this->model(Server::class)->enumerate($op, $options);
    }

    /**
     * Retrieves an existing server
     *
     * @param string $id The server UUID
     *
     * @return Server
     */
    public function getServer($id): Server
    {
        return $this->model(Server::class, ['id' => $id]);
    }

    /**
     * Creates or imports a new keypair
     *
     * @param array $options {@see \Rackspace\Compute\v2\Api::postOsKeypairs}
     *
     * @return Keypair
     */
    public function createKeypair(array $options): Keypair
    {
        return $this->model(Keypair::class)->create($options);
    }

    /**
     * Lists existing keypairs
     *
     * @param array $options {@see \Rackspace\Compute\v2\Api::getOskeypairs}
     *
     * @return \Generator
     */
    public function listKeypairs(array $options = []): \Generator
    {
        return $this->model(Keypair::class)->enumerate($this->api->getOskeypairs(), $options);
    }

    /**
     * Retrieves an existing keypair.
     *
     * @param string $name
     *
     * @return Keypair
     */
    public function getKeypair($name): Keypair
    {
        return $this->model(Keypair::class, ['name' => $name]);
    }

    /**
     * Returns a list of flavors
     *
     * @param bool  $detail  Indicates whether to provide detailed information
     * @param array $options {@see \Rackspace\Compute\v2\Api::getFlavors}
     *
     * @return \Generator
     */
    public function listFlavors($detail = false, array $options = []): \Generator
    {
        $op = $detail ? $this->api->getFlavorsDetail() : $this->api->getFlavors();
        return $this->model(Flavor::class)->enumerate($op, $options);
    }

    /**
     * Retrieves an existing flavor
     *
     * @param string $id
     *
     * @return Flavor
     */
    public function getFlavor($id): Flavor
    {
        return $this->model(Flavor::class, ['id' => $id]);
    }

    /**
     * Retrieves a list of OS images
     *
     * @param bool  $detail
     * @param array $options {@see \Rackspace\Compute\v2\Api::getImages}
     *
     * @return \Generator
     */
    public function listImages($detail = false, array $options = []): \Generator
    {
        $op = $detail ? $this->api->getImagesDetail() : $this->api->getImages();
        return $this->model(Image::class)->enumerate($op, $options);
    }

    /**
     * Retrieves an existing image by its UUID
     *
     * @param string $id
     *
     * @return Image
     */
    public function getImage($id): Image
    {
        return $this->model(Image::class, ['id' => $id]);
    }

    /**
     * Retrieves an existing name by its name
     *
     * @param string $name
     *
     * @return Image|false
     */
    public function getImageByName($name)
    {
        foreach ($this->listImages(false, ['name' => $name]) as $image) {
            if ($image->name == $name) {
                return $image;
            }
        }

        return false;
    }

    /**
     * Retrieves a list of networks
     *
     * @param array $options {@see \Rackspace\Compute\v2\Api::getOsnetworksv2}
     *
     * @return \Generator
     */
    public function listNetworks(array $options = []): \Generator
    {
        return $this->model(Network::class)->enumerate($this->api->getOsnetworksv2(), $options);
    }

    /**
     * Creates a network
     *
     * @param array $options{@see \Rackspace\Compute\v2\Api::postOsnetworksv2}
     *
     * @return Network
     */
    public function createNetwork(array $options): Network
    {
        return $this->model(Network::class)->create($options);
    }

    /**
     * Retrieves a network
     *
     * @param string $id
     *
     * @return Network
     */
    public function getNetwork($id): Network
    {
        return $this->model(Network::class, ['id' => $id]);
    }
}
