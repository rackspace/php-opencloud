<?php

namespace Rackspace\LoadBalancer\v1\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Statistics resource in the LoadBalancer v1 service
 *
 * @property \Rackspace\LoadBalancer\v1\Api $api
 */
class Statistics extends AbstractResource implements Retrievable
{
    /**
     * @var integer
     */
    public $connectTimeOut;

    /**
     * @var integer
     */
    public $connectError;

    /**
     * @var integer
     */
    public $connectFailure;

    /**
     * @var integer
     */
    public $dataTimedOut;

    /**
     * @var integer
     */
    public $keepAliveTimedOut;

    /**
     * @var integer
     */
    public $maxConn;

    /**
     * @var integer
     */
    public $currentConn;

    /**
     * @var integer
     */
    public $connectTimeOutSsl;

    /**
     * @var integer
     */
    public $connectErrorSsl;

    /**
     * @var integer
     */
    public $connectFailureSsl;

    /**
     * @var integer
     */
    public $dataTimedOutSsl;

    /**
     * @var integer
     */
    public $keepAliveTimedOutSsl;

    /**
     * @var integer
     */
    public $maxConnSsl;

    /**
     * @var integer
     */
    public $currentConnSsl;

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getStatistics());
        return $this->populateFromResponse($response);
    }
}