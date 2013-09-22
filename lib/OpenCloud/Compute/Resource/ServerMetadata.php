<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright Copyright 2013 Rackspace US, Inc. See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.6.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Compute\Resource;

use OpenCloud\Common\Lang;
use OpenCloud\Common\Metadata;
use OpenCloud\Common\Exceptions;

/**
 * This class handles specialized metadata for OpenStack Server objects (metadata 
 * items can be managed individually or in aggregate).
 *
 * Server metadata is a weird beast in that it has resource representations
 * and HTTP calls to set the entire server metadata as well as individual
 * items.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class ServerMetadata extends Metadata
{

    private $parent;   // the parent object
    private $key;      // the metadata item (if supplied)
    private $url;      // the URL of this particular metadata item or block

    /**
     * Contructs a Metadata object associated with a Server or Image object
     *
     * @param object $parent either a Server or an Image object
     * @param string $key the (optional) key for the metadata item
     * @throws MetadataError
     */
    public function __construct(Server $parent, $key = null)
    {
        // construct defaults
        $this->setParent($parent);

        // set the URL according to whether or not we have a key
        if ($this->getParent()->id) {
            $this->url = $this->getParent()->url('metadata');
            $this->key = $key;

            // in either case, retrieve the data
            $response = $this->getParent()->getService()->request($this->url());

            // @codeCoverageIgnoreStart
            if ($response->httpStatus() >= 300) {
                throw new Exceptions\MetadataError(sprintf(
                    Lang::translate('Unable to retrieve metadata [%s], response [%s]'),
                    $this->Url(),
                    $response->HttpBody()
                ));
            }
            // @codeCoverageIgnoreEnd

            $this->getLogger()->info(
                Lang::translate('Metadata for [{url}] is [{body}]'), 
                array(
                    'url'  => $this->url(), 
                    'body' => $response->httpBody()
                )
            );

            // parse and assign the server metadata
            $object = json_decode($response->HttpBody());
            $this->checkJsonError();
            
            if (isset($object->metadata)) {
                $this->populate($object->metadata);
            }
        }
    }

    public function setParent(Server $parent)
    {
        $this->parent = $parent;
        return $this;
    }
    
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Returns the URL of the metadata (key or block)
     *
     * @return string
     * @param string $subresource not used; required for strict compatibility
     * @throws ServerUrlerror
     */
    public function url($subresource = '')
    {
        if (!isset($this->url)) {
            throw new Exceptions\ServerUrlError(
                'Metadata has no URL (new object)'
            );
        }

        if ($this->key) {
            //careful, you don't want to overwrite the existing this->_url field
            $urlArray = array();
            foreach($this->url as $url) {
                $url .= '/' . $this->key;
                $urlArray[] = $url;
            }
            return $urlArray;
        } else {
            return $this->url;
        }
    }

    /**
     * Sets a new metadata value or block
     *
     * Note that, if you're setting a block, the block specified will
     * *entirely replace* the existing block.
     *
     * @api
     * @return void
     * @throws MetadataCreateError
     */
    public function create()
    {
        // perform the request
        $response = $this->getParent()->getService()->request(
            $this->url(),
            'PUT',
            array(),
            $this->getMetadataJson()
        );

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() >= 300) {
            throw new \OpenCloud\Common\Exceptions\MetadataCreateError(sprintf(
                Lang::translate('Error setting metadata on [%s], response [%s]'),
                $this->Url(),
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Updates a metadata key or block
     *
     * @api
     * @return void
     * @throws MetadataUpdateError
     */
    public function update()
    {
        // perform the request
        $response = $this->getParent()->getService()->request(
            $this->url(),
            'POST',
            array(),
            $this->getMetadataJson()
        );

        // @codeCoverageIgnoreStart
        if ($response->HttpStatus() >= 300) {
            throw new Exceptions\MetadataUpdateError(sprintf(
                Lang::translate('Error updating metadata on [%s], response [%s]'),
                $this->Url(),
                $response->HttpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Deletes a metadata key or block
     *
     * @api
     * @return void
     * @throws MetadataDeleteError
     */
    public function delete()
    {
        // perform the request
        $response = $this->getParent()->getService()->request(
            $this->url(),
            'DELETE',
            array()
        );

        // @codeCoverageIgnoreStart
        if ($response->httpStatus() >= 300) {
            throw new Exceptions\MetadataDeleteError(sprintf(
                Lang::translate('Error deleting metadata on [%s], response [%s]'),
                $this->url(),
                $response->httpBody()
            ));
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Overrides the base setter method, since the metadata key can be
     * anything (no name-checking is required)
     *
     * @param string $key
     * @param string $value
     * @return void
     * @throws MetadataKeyError
     */
    public function __set($key, $value)
    {
        // if a key was supplied when creating the object, then we can't set
        // any other values
        if ($this->key && $key != $this->key) {
            throw new Exceptions\MetadataKeyError(sprintf(
                Lang::translate('You cannot set extra values on [%s]'),
                implode(", ", $this->Url())
            ));
        }

        // otherwise, just set it;
        parent::__set($key, $value);
    }

    /********** PRIVATE METHODS **********/

    /**
     * Builds a metadata JSON string
     *
     * @return string
     * @throws MetadataJsonError
     */
    private function getMetadataJson()
    {
        $object = (object) array(
            'meta'     => new \stdClass,
            'metadata' => new \stdClass
        );

        // different element if only a key is set
        if ($name = $this->key) {
            $object->meta->$name = $this->$name;
        } else {
            $object->metadata = new \stdClass();
            foreach ($this->keylist() as $key) {
                $object->metadata->$key = (string) $this->$key;
            }
        }

        $json = json_encode($object);
        $this->checkJsonError();
        
        return $json;
    }

}
