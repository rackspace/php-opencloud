<?php declare(strict_types=1);

namespace Rackspace\ObjectStoreCDN\v1\Models;

use OpenStack\Common\Resource\Deletable;
use OpenStack\Common\Resource\OperatorResource;

class RackspaceObject extends OperatorResource implements Deletable
{
    /** @var string */
    public $containerName;

    /** @var string */
    public $name;

    public function delete()
    {
        $this->executeWithState($this->api->deleteObject());
    }
}
