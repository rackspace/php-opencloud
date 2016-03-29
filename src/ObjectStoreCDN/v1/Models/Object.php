<?php declare(strict_types=1);

namespace Rackspace\ObjectStoreCDN\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Deletable;

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