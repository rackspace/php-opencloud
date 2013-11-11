<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Collection;

use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\Common\Exceptions\ResourceBucketException;

class ResourceBucket implements \Iterator
{
    const LIMIT = 100;
    const METHOD = 'GET';

    private $parentList;

    private $resources = array();

    public $marker;

    private $position;

    public static function factory(ResourceList $parentList, $parentMarker)
    {
        $item = new self();

        $item->parentList = $parentList;
        $item->marker = $parentMarker;
        $item->position = 0;
        return $item->populateFromUrl();
    }

    public function populateFromUrl()
    {
        $url = clone $this->parentList->getBaseUrl();
        $url->setQuery(array('marker' => $this->marker, 'limit' => static::LIMIT));

        $response = $this->parentList
            ->resourceParent
            ->getClient()
            ->createRequest(static::METHOD, $url, static::getHeaders(), static::getBody(), static::getCurlOptions())
            ->send();

        if (empty($response->getDecodedBody()) || $response->getStatusCode() == 204) {
            return false;
        }

        return $this->setResources($response->getDecodedBody());
    }

    public function setResources($string)
    {
        $this->resources = $string;
        return $this;
    }

    public static function getHeaders()
    {
        return array();
    }

    public static function getBody()
    {
        return null;
    }

    public static function getCurlOptions()
    {
        return array();
    }

    public function instantiateCurrentResource()
    {
        $className = $this->parentList->resourceClass;
        $parent = $this->parentList->resourceParent;
        $data = $this->current();
        $getter = 'get' . ucfirst($className);

        if (method_exists($parent, $className)) {
            // $parent->server($data)
            return call_user_func(array($parent, $className), $data);
        } elseif (method_exists($parent, $getter)) {
            // $parent->getServer($data)
            return call_user_func(array($parent, $getter), $data);
        } elseif (method_exists($parent, 'resource')) {
            // $parent->resource('Server', $data)
            return $parent->resource($className, $data);
        } else {
            throw new ResourceBucketException(sprintf(
                'The %s parent object does not have any methods to instantiate %s',
                get_class($parent),
                $className
            ));
        }
    }

    public function current()
    {
        return $this->resources[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
        $this->setCurrentMarkerValue();
    }

    public function prev()
    {
        $this->position--;
        $this->setCurrentMarkerValue();
    }

    public function setCurrentMarkerValue()
    {
        if (!$this->valid()) {
            return;
        }

        $markerKey = $this->parentList->markerKey;
        $object = $this->current();

        if (!isset($object->$markerKey)) {
            throw new ResourceBucketException(sprintf(
                'The marker key [%s] is not set on this object. It must be set on the object returned from the API: $object->%s',
                $markerKey, $markerKey
            ));
        }

        $this->marker = $object->$markerKey;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->resources[$this->position]);
    }

}