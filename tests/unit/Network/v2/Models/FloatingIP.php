<?php declare(strict_types=1);

namespace Rackspace\Test\Network\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Creatable;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;
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