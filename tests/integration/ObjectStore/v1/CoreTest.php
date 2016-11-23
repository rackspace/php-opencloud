<?php declare(strict_types=1);

namespace Rackspace\Integration\ObjectStore\v1;

use Rackspace\Integration\Utils;
use Rackspace\Rackspace;

class CoreTest extends \OpenStack\Integration\ObjectStore\v1\CoreTest
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
