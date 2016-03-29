<?php declare(strict_types=1);

namespace Rackspace\Image\v1\Models;

use OpenCloud\Common\Resource\AbstractResource;
use OpenCloud\Common\Resource\Deletable;
use OpenCloud\Common\Resource\Listable;
use OpenCloud\Common\Resource\Retrievable;
use OpenCloud\Common\Resource\Updateable;

/**
 * Represents a Image resource in the Image v1 service
 *
 * @property \Rackspace\Image\v1\Api $api
 */
class Image extends AbstractResource implements Updateable, Listable, Deletable, Retrievable
{
    /**
     * @var string
     */
    public $containerFormat;

    /**
     * @var integer
     */
    public $minRam;

    /**
     * @var string
     */
    public $updatedAt;

    /**
     * @var string
     */
    public $owner;

    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $flavorClasses;

    /**
     * @var string
     */
    public $vmMode;

    /**
     * @var string
     */
    public $id;

    /**
     * @var integer
     */
    public $size;

    /**
     * @var string
     */
    public $osDistro;

    /**
     * @var string
     */
    public $imageType;

    /**
     * @var string
     */
    public $self;

    /**
     * @var string
     */
    public $diskFormat;

    /**
     * @var string
     */
    public $schema;

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    public $tags;

    /**
     * @var string
     */
    public $visibility;

    /**
     * @var string
     */
    public $autoDiskConfig;

    /**
     * @var integer
     */
    public $minDisk;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $checksum;

    /**
     * @var string
     */
    public $createdAt;

    /**
     * @var string
     */
    public $cacheInNova;

    /**
     * @var boolean
     */
    public $protected;

    /**
     * @var string
     */
    public $osType;

    /**
     * @var string
     */
    public $releaseId;

    /**
     * @var string
     */
    public $buildCore;

    /**
     * @var string
     */
    public $options;

    /**
     * @var string
     */
    public $releaseVersion;

    /**
     * @var string
     */
    public $platformTarget;

    /**
     * @var string
     */
    public $buildManaged;

    /**
     * @var string
     */
    public $visibleManaged;

    /**
     * @var string
     */
    public $source;

    /**
     * @var string
     */
    public $uiDefaultShow;

    /**
     * @var string
     */
    public $releaseBuildDate;

    /**
     * @var string
     */
    public $visibleCore;

    /**
     * @var string
     */
    public $buildRackconnect;

    /**
     * @var string
     */
    public $visibleRackconnect;

    /**
     * @var string
     */
    public $osVersion;

    /**
     * @var string
     */
    public $architecture;

    protected $aliases = [
        'container_format'                      => 'containerFormat',
        'min_ram'                               => 'minRam',
        'updated_at'                            => 'updatedAt',
        'flavor_classes'                        => 'flavorClasses',
        'vm_mode'                               => 'vmMode',
        'os_distro'                             => 'osDistro',
        'image_type'                            => 'imageType',
        'disk_format'                           => 'diskFormat',
        'auto_disk_config'                      => 'autoDiskConfig',
        'min_disk'                              => 'minDisk',
        'created_at'                            => 'createdAt',
        'cache_in_nova'                         => 'cacheInNova',
        'os_type'                               => 'osType',
        'com.rackspace__1__release_id'          => 'releaseId',
        'com.rackspace__1__build_core'          => 'buildCore',
        'com.rackspace__1__options'             => 'options',
        'com.rackspace__1__release_version'     => 'releaseVersion',
        'com.rackspace__1__platform_target'     => 'platformTarget',
        'com.rackspace__1__build_managed'       => 'buildManaged',
        'com.rackspace__1__visible_managed'     => 'visibleManaged',
        'com.rackspace__1__source'              => 'source',
        'com.rackspace__1__ui_default_show'     => 'uiDefaultShow',
        'com.rackspace__1__release_build_date'  => 'releaseBuildDate',
        'com.rackspace__1__visible_core'        => 'visibleCore',
        'com.rackspace__1__build_rackconnect'   => 'buildRackconnect',
        'com.rackspace__1__visible_rackconnect' => 'visibleRackconnect',
        'org.openstack__1__os_version'          => 'osVersion',
        'org.openstack__1__architecture'        => 'architecture',
    ];

    /**
     * {@inheritDoc}
     */
    public function update()
    {
        $response = $this->executeWithState($this->api->putImage());
        $this->populateFromResponse($response);
    }

    /**
     * {@inheritDoc}
     */
    public function delete()
    {
        $this->executeWithState($this->api->deleteImage());
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve()
    {
        $response = $this->executeWithState($this->api->getImage());
        $this->populateFromResponse($response);
    }
}
