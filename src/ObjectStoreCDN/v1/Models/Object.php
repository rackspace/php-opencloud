<?php

namespace Rackspace\ObjectStoreCDN\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Deletable;

class Object extends AbstractResource implements Deletable
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