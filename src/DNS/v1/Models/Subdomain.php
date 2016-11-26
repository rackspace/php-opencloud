<?php declare(strict_types=1);

namespace Rackspace\DNS\v1\Models;

use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\OperatorResource;

/**
 * Represents a Subdomain resource in the DNS v1 service
 *
 * @property \Rackspace\DNS\v1\Api $api
 */
class Subdomain extends OperatorResource implements Listable
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $comment;

    /**
     * @var string
     */
    public $updated;

    /**
     * @var string
     */
    public $emailAddress;

    /**
     * @var string
     */
    public $created;
}
