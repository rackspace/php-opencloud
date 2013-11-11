<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Collection;


class ResourceList implements \Iterator
{

    private $elements;

    private $position;

    public function __construct()
    {
        $this->rewind();
    }

    public function current()
    {
        return $this->elements[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
        $this->updateMarkerToCurrent();
    }

    public function updateMarkerToCurrent()
    {
        $element = $this->elements[$this->position];
        if (isset($element->{$this->markerKey})) {
            $this->currentMarker = $element->{$this->markerKey};
        }
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function count()
    {
        return count($this->elements);
    }

    public function valid()
    {
        if (isset($this->elements[$this->position])) {
            return true;
        } else {
            $before = $this->count();
            $this->appendNewCollection();
            if ($this->count() > $before) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function appendElements(array $elements)
    {
        $this->elements += $elements;
        return $this;
    }

    public function appendNewCollection()
    {
        $url = clone $this->baseUrl;
        $url->setQuery(array('marker' => $this->currentMarker, 'limit' => $this->limit));

        $response = $this->resourceParent
            ->getClient()
            ->createRequest($this->method, $url)
            ->send();

        if (empty($response->getDecodedBody()) || $response->getStatusCode() == 204) {
            return false;
        }

        return $this->setElements($response->getDecodedBody());
    }

} 