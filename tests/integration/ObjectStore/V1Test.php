<?php declare(strict_types=1);

namespace Rackspace\Integration\ObjectStore;

use Rackspace\Integration\Utils;
use Rackspace\Rackspace;

class V1Test extends \OpenStack\Integration\ObjectStore\V1Test
{
    private $service;

    protected function getService()
    {
        if (null === $this->service) {
            $this->service = (new Rackspace())->objectStoreV1(Utils::getAuthOpts());
        }

        return $this->service;
    }
}