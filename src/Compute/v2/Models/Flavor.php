<?php declare(strict_types=1);

namespace Rackspace\Compute\v2\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Transport\Utils;

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
    public $ephemeralDisks;

    /**
     * @var object
     */
    public $extraSpecs;

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
        'OS-FLV-EXT-DATA:ephemeral'         => 'ephemeralDisks',
        'OS-FLV-WITH-EXT-SPECS:extra_specs' => 'extraSpecs',
        'rxtx_factor'                       => 'rxtxFactor',
    ];

    protected $resourceKey = 'flavor';
    protected $resourcesKey = 'flavors';

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getFlavorId());
        $this->populateFromResponse($response);
    }

    /**
     * Retrieves additional specification information for this flavor.
     *
     * @return array
     */
    public function retrieveExtraSpecs()
    {
        $response = $this->executeWithState($this->api->getFlavorExtraSpecs());
        return (array)Utils::jsonDecode($response)['extra_specs'];
    }
}