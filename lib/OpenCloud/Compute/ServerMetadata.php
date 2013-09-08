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

namespace OpenCloud\Compute;

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

    private $_parent;   // the parent object
    private $_key;      // the metadata item (if supplied)
    private $_url;      // the URL of this particular metadata item or block

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
        $this->_parent = $parent;

        // set the URL according to whether or not we have a key
        if ($this->Parent()->id) {
            $this->_url = $this->Parent()->Url() . '/metadata';
            $this->_key = $key;

            // in either case, retrieve the data
            $response = $this->Parent()->Service()->Request($this->Url());

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
            $obj = json_decode($response->HttpBody());

            if ((!$this->CheckJsonError()) && isset($obj->metadata)) {
                foreach($obj->metadata as $k => $v) {
                    $this->$k = $v;
                }
            }
        }
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
        if (!isset($this->_url)) {
            throw new Exceptions\ServerUrlError(
                'Metadata has no URL (new object)'
            );
        }

        if ($this->_key) {
            return $this->_url . '/' . $this->_key;
        } else {
            return $this->_url;
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
        $response = $this->parent()->Service()->Request(
            $this->Url(),
            'PUT',
            array(),
            $this->GetMetadataJson()
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
        $response = $this->parent()->getService()->Request(
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
        $response = $this->parent()->getService()->Request(
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
     * Returns the parent Server object
     *
     * @return Server
     */
    public function parent()
    {
        return $this->_parent;
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
        if ($this->_key && $key != $this->_key) {
            throw new Exceptions\MetadataKeyError(sprintf(
                Lang::translate('You cannot set extra values on [%s]'),
                $this->Url()
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
        if ($name = $this->_key) {
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
