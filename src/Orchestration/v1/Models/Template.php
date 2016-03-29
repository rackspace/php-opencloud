<?php declare(strict_types=1);

namespace Rackspace\Orchestration\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Retrievable;

/**
 * Represents a Template resource in the Network v1 service
 *
 * @property \Rackspace\Network\v2\Api $api
 */
class Template extends AbstractResource implements Retrievable
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $heatTemplateVersion;

    /**
     * @var object
     */
    public $outputs;

    /**
     * @var object
     */
    public $parameters;

    /**
     * @var object
     */
    public $resources;

    protected $aliases = [
        'heat_template_version' => 'heatTemplateVersion',
    ];

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getTemplate());
        $this->populateFromResponse($response);
    }
}