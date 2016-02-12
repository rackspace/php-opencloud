<?php

namespace Rackspace\DNS\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;

/**
 * Represents a Subdomain resource in the DNS v1 service
 *
 * @property \Rackspace\DNS\v1\Api $api
 */
class Subdomain extends AbstractResource implements Listable
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
