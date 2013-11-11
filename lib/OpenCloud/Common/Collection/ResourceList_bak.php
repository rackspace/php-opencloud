<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Collection;

use Guzzle\Http\Url;
use OpenCloud\Common\PersistentObject;

class ResourceList implements \OuterIterator
{
    const DEFAULT_MARKER_VALUE = 'name';

    private $buckets = array();

    private $bucketPosition;

    private $currentResourceMarker;

    private $baseUrl;

    public $resourceParent;

    public $resourceClass;

    public $markerKey;

    protected $bucketClass = 'OpenCloud\\Common\\Collection\\ResourceBucket';

    public function __construct()
    {
        $this->rewind();
    }

    public static function factory($resourceParent, Url $baseUrl, $resourceClass, $markerKey = self::DEFAULT_MARKER_KEY)
    {
        $self = new self();
        $self->resourceParent = $resourceParent;
        $self->baseUrl = $baseUrl;
        $self->resourceClass = $resourceClass;
        $self->markerKey = $markerKey;

        $self->setBucket(0, $self->buildNewBucket());

        return $self;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getInnerIterator()
    {
        return new \ArrayIterator($this->buckets);
    }

    public function valid()
    {
        if ($this->current()->valid()) {
            return true;
        } elseif (false !== ($nextBucket = $this->buildNewBucket())) {
            $this->setNextBucket($nextBucket);
            return true;
        } else {
            return false;
        }
    }

    public function next()
    {
        $resource = $this->current()->next();
        $this->currentResourceMarker = $this->current()->marker;
        return $resource;
    }

    public function prev()
    {
        $resource = $this->current()->rewind();
        $this->currentResourceMarker = $this->current()->marker;
        return $resource;
    }

    public function rewind()
    {
        $this->currentResourceMarker = null;
        $this->bucketPosition = 0;
    }

    public function current()
    {
        return $this->buckets[$this->bucketPosition];
    }

    public function key()
    {
        return $this->bucketPosition;
    }

    public function buildNewBucket()
    {
        $class = $this->bucketClass;
        return $class::factory($this, $this->currentResourceMarker);
    }

    public function setNextBucket(ResourceBucket $nextBucket)
    {
        $this->bucketPosition++;
        return $this->setBucket($this->bucketPosition, $nextBucket);
    }

    public function setBucket($offset, ResourceBucket $bucket)
    {
        $this->buckets[$offset] = $bucket;
        return $this;
    }

    public function currentResource()
    {
        return $this->current()->instantiateCurrentResource();
    }

    public function nextBucket()
    {
        $bucket = $this->buckets[$this->bucketPosition++];
        $this->currentResourceMarker = $bucket->marker;
        return $bucket;
    }

    public function prevBucket()
    {
        if ($this->bucketPosition > 0) {
            $bucket = $this->buckets[$this->bucketPosition--];
            $this->currentResourceMarker = $item->marker;
            return $bucket;
        } else {
            return false;
        }
    }

}