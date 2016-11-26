<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Creatable;
use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;
use OpenStack\Common\Resource\Updateable;
use Rackspace\Network\v2\Api;

/**
 * @property Api $api
 */
class FloatingIP extends AbstractResource implements Creatable, Retrievable, Updateable, Deletable, Listable
{
    /** @var string */
    public $floatingNetworkId;

    /** @var string */
    public $routerId;

    /** @var string */
    public $fixedIpAddress;

    /** @var string */
    public $floatingIpAddress;

    /** @var string */
    public $tenantId;

    /** @var string */
    public $status;

    /** @var string */
    public $portId;

    /** @var string */
    public $id;

    public function create(array $userOptions): Creatable
    {
        $response = $this->execute($this->api->createFloatingIp());
        return $this->populateFromResponse($response);
    }
}
