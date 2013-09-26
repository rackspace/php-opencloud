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
    protected $key;      // the metadata item (if supplied)
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
        if ($this->getParent()->getId()) {
            
            $this->url = $this->getParent()->url('metadata');
            $this->key = $key;

            // in either case, retrieve the data
            $response = $this->getParent()
                ->getClient()
                ->get($this->url())
                ->send();

            $this->getLogger()->info(
                Lang::translate('Metadata for [{url}] is [{body}]'), 
                array(
                    'url'  => $this->url(), 
                    'body' => $response->getBody()
                )
            );

            // parse and assign the server metadata
            $object = $response->getDecodedBody();

            if (isset($object->metadata)) {
                foreach ($object->metadata as $key => $value) {
                    $this->$key = $value;
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
        if (!isset($this->url)) {
            throw new Exceptions\ServerUrlError(
                'Metadata has no URL (new object)'
            );
        }

        if ($this->key) {
            return $this->url . '/' . $this->key;
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
        return $this->getClient()->put($this->url(), array(), $this->getMetadataJson());
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
        return $this->getClient()
            ->post($this->url(), array(), $this->getMetadataJson())
            ->send();
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
        return $this->getClient()->delete($this->url(), array());
    }

    public function __set($key, $value)
    {
        // if a key was supplied when creating the object, then we can't set
        // any other values
        if ($this->key && $key != $this->key) {
            throw new Exceptions\MetadataKeyError(sprintf(
                Lang::translate('You cannot set extra values on [%s]'),
                $this->Url()
            ));
        }

        // otherwise, just set it;
        parent::__set($key, $value);
    }
    
    /**
     * Builds a metadata JSON string
     *
     * @return string
     * @throws MetadataJsonError
     */
    private function getMetadataJson()
    {
        $object = (object) array(
            'meta'     => (object) array(),
            'metadata' => (object) array()
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
