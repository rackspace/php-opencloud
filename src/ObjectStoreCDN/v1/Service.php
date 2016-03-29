<?php declare(strict_types=1);

namespace Rackspace\ObjectStoreCDN\v1;

use OpenCloud\Common\Service\AbstractService;
use Rackspace\ObjectStoreCDN\v1\Models\Container;

class Service extends AbstractService
{
    /**
     * Retrieves a collection of container resources in a generator format.
     *
     * @param array         $options {@see \OpenStack\ObjectStore\v1\Api::getAccount}
     * @param callable|null $mapFn   Allows a function to be mapped over each element in the collection.
     *
     * @return \Generator
     */
    public function listContainers(array $options = [], callable $mapFn = null): \Generator
    {
        $options = array_merge($options, ['format' => 'json']);
        return $this->model(Container::class)->enumerate($this->api->getAccount(), $options, $mapFn);
    }

    /**
     * Retrieves a Container object and populates its name according to the value provided. Please note that the
     * remote API is not contacted.
     *
     * @param string $name The unique name of the container
     *
     * @return Container
     */
    public function getContainer($name = null): Container
    {
        return $this->model(Container::class, ['name' => $name]);
    }
}