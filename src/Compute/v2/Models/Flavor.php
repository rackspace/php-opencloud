<?php

namespace Rackspace\Compute\v2\Models;

use OpenStack\Common\Resource\AbstractResource;
use OpenStack\Common\Resource\Listable;
use OpenStack\Common\Resource\Retrievable;

/**
 * Represents a Flavor resource in the Compute v2 service
 *
 * @property \Rackspace\Compute\v2\Api $api
 */
class Flavor extends AbstractResource implements Listable, Retrievable
{
    /**
     * @var integer
     */
    public $oSFLVEXTDATAephemeral;

    /**
     * @var object
     */
    public $oSFLVWITHEXTSPECSextraSpecs;

    /**
     * @var integer
     */
    public $disk;

    /**
     * @var string
     */
    public $id;

    /**
     * @var array
     */
    public $links;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $ram;

    /**
     * @var double
     */
    public $rxtxFactor;

    /**
     * @var string
     */
    public $swap;

    /**
     * @var integer
     */
    public $vcpus;

    protected $aliases = [
        'OS-FLV-EXT-DATA:ephemeral'         => 'oSFLVEXTDATAephemeral',
        'OS-FLV-WITH-EXT-SPECS:extra_specs' => 'oSFLVWITHEXTSPECSextraSpecs',
        'rxtx_factor'                       => 'rxtxFactor',
    ];

    protected $resourceKey = 'flavor';

    protected $resourcesKey = 'flavors';

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getFlavor());
        return $this->populateFromResponse($response);
    }
}