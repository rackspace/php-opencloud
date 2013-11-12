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
use OpenCloud\Common\Exceptions\CollectionException;
use OpenCloud\Common\Exceptions\InvalidArgumentError;
use OpenCloud\Common\Http\Message\Formatter;

class ResourceIterator extends Collection implements \Iterator
{

    private $position;
    private $options;
    private $baseUrl;
    private $resourceParent;
    private $currentMarker;
    private $nextUrl;

    private $defaults = array(
        'markerKey'   => 'name',
        'limit'       => 10000,
        'pageLimit'   => 100,
        'method'      => 'GET',
        'headers'     => array(),
        'body'        => null,
        'curlOptions' => array(),
        'linksKey'    => 'links'
    );

    private $required = array('resourceClass', 'collectionKey');

    public static function factory($parent, Url $url, array $params = array())
    {
        $list = new static();

        $options = $params + $list->defaults;

        if ($missing = array_diff($list->required, array_keys($options))) {
            throw new InvalidArgumentError(sprintf('%s is a required option', implode(',', $missing)));
        }

        $list->setOptions($options)
            ->setBaseUrl($url)
            ->setResourceParent($parent)
            ->setup();

        return $list;
    }

    public function setup()
    {
        $this->rewind();
        $this->appendNewCollection();
    }

    public function setResourceParent($parent)
    {
        $this->resourceParent = $parent;
        return $this;
    }

    public function setBaseUrl(Url $url)
    {
        $this->baseUrl = $url;
        return $this;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function getOption($key)
    {
        return (isset($this->options[$key])) ? $this->options[$key] : null;
    }

    public function current()
    {
        return $this->constructResource($this->currentElement());
    }

    public function currentElement()
    {
        return $this->elements[$this->position];
    }

    public function constructResource($object)
    {
        $className = $this->getOption('resourceClass');
        $parent = $this->resourceParent;

        $getter = sprintf('get%s', ucfirst($className));

        if (method_exists($parent, $className)) {
            // $parent->server($data)
            return call_user_func(array($parent, $className), $object);
        } elseif (method_exists($parent, $getter)) {
            // $parent->getServer($data)
            return call_user_func(array($parent, $getter), $object);
        } elseif (method_exists($parent, 'resource')) {
            // $parent->resource('Server', $data)
            return $parent->resource($className, $object);
        } else {
            throw new CollectionException(sprintf(
                'The %s parent object does not have any methods to instantiate %s',
                get_class($parent),
                $className
            ));
        }
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
        if (!isset($this->elements[$this->position])) {
            return;
        }

        $element = $this->elements[$this->position];
        $key = $this->getOption('markerKey');
        if (isset($element->$key)) {
            $this->currentMarker = $element->$key;
        }
    }

    public function rewind()
    {
        $this->position = 0;
        $this->currentMarker = null;
    }

    public function count()
    {
        return count($this->elements);
    }

    public function valid()
    {
        if ($this->position >= $this->getOption('limit')) {
            return false;
        } elseif (isset($this->elements[$this->position])) {
            return true;
        } else {
            $before = $this->count();
            $this->appendNewCollection();
            return ($this->count() > $before) ? true : false;
        }
    }

    public function appendElements(array $elements)
    {
        $this->elements = array_merge($this->elements, $elements);
        return $this;
    }

    public function appendNewCollection()
    {
        $response = $this->resourceParent
            ->getClient()
            ->createRequest(
                $this->getOption('method'),
                $this->constructNextUrl(),
                $this->getOption('headers'),
                $this->getOption('body'),
                $this->getOption('curlOptions')
            )
            ->send();

        if (!($body = Formatter::decode($response)) || $response->getStatusCode() == 204) {
            return false;
        }

        if ($nextUrl = $this->extractNextLink($body)) {
            $this->nextUrl = $nextUrl;
        }

        return $this->appendElements($this->parseResponseBody($body));
    }

    public function extractNextLink($body)
    {
        $key = $this->getOption('linksKey');

        if (isset($body->$key)) {
            foreach ($body->$key as $link) {
                if (isset($link->rel) && $link->rel == 'next') {
                    return $link->href;
                }
            }
        }

        return false;
    }

    public function constructNextUrl()
    {
        if (!$url = $this->nextUrl) {
            $url = clone $this->baseUrl;
            $url->setQuery(array('marker' => $this->currentMarker, 'limit' => $this->getOption('pageLimit')));
        }

        return $url;
    }

    public function parseResponseBody($body)
    {
        $collectionKey = $this->getOption('collectionKey');

        $data = array();

        if (is_array($body)) {
            $data = $body;
        } elseif (isset($body->$collectionKey)) {
            if (null !== ($elementKey = $this->getOption('collectionElementKey'))) {
                // The object has element levels which need to be iterated over
                foreach ($body->$collectionKey as $item) {
                    $subValues = $item->$elementKey;
                    unset($item->$elementKey);
                    $data[] = array_merge((array) $item, (array) $subValues);
                }
            } else {
                // The object has a top-level collection name only
                $data = $body->$collectionKey;
            }
        }

        return $data;
    }

    public function populateAll()
    {
        while ($this->valid()) {
            $this->next();
        }
    }

    public function getElement($offset)
    {
        return (!$this->offsetExists($offset)) ? false : $this->constructResource($this->offsetGet($offset));
    }

} 