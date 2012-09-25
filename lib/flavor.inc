<?php
/**
 * A flavor object, which defines RAM, disk, and other settings for a virtual
 * machine.
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Compute;

require_once('base.inc');

/**
 * The Flavor class represents a flavor defined by the Compute service
 * 
 * At its simplest, a Flavor represents a combination of RAM, disk space,
 * and compute CPUs, though there are other extended attributes.
 */
class Flavor extends \OpenCloud\Base {
    public
		$status,
		$updated,
		$vcpus,
		$disk,
		$name,
		$links,
		$rxtx_factor,
		$ram,
		$id,
		$swap;
    private
        $service,   // the compute service associated with this image
        $flavorurl;  // the URL of this image

    /**
     * Creates a new flavor object; if ID is specified, then the
     * flavor with the specified ID is retrieved
     *
     * @param $id - the ID of the flavor to retrieve; otherwise, an empty
     *    Flavor object is created.
     * @throws FlavorError, JsonError, UnknownError
     */
    public function __construct($compute, $id=NULL) {
        $this->service = $compute;

        // if ID is specified, retrieve it
        if (is_array($id) || is_object($id)) {
        	foreach($id as $name => $value)
        		$this->$name = $value;
        }
        elseif ($id) {
            $this->flavorurl = $this->Service()->Url('flavors/'.$id);
            $response = $this->service->Request($this->flavorurl);
            if ($response->HttpStatus() >= 300)
                throw new FlavorError(
                	sprintf(_('Unable to retrieve flavor [%s]'), $id));
            else if (!$response->HttpBody())
                throw new UnknownError(
                	sprintf(_('Unexpected result for flavor [%s]'), $id));
            else {
            	$this->debug('Flavor JSON [%s]', $response->HttpBody());
                $obj = json_decode($response->HttpBody());
                if ($this->CheckJsonError())
                	return FALSE;
                if (!isset($obj->flavor))
                    throw new FlavorError(
                        sprintf(_('No flavor element in JSON for flavor [%s]'),
                            $id));
                else
                    foreach($obj->flavor as $property => $value)
                        $this->$property = $value;

            }
        }
    }

    /**
     * Returns the URL of the flavor
     *
     * @returns FALSE if the object is empty; otherwise the "self" URL link
     */
    public function Url() {
    	// return FALSE if no ID
    	if (!isset($this->id))
    		return FALSE;

    	// otherwise, find the "self" link
    	foreach($links as $link) {
    		if ($link->rel == 'self')
    			return $link->href;
    	}

    	// if we're here, we haven't found a SELF link
    	return FALSE;
    }

    /**
     * Validates extension attributes
     */
    public function __set($name, $value) {
        $this->SetProperty($name, $value, $this->Service()->namespaces());
    }

    /**
     * Returns the service associated with this Flavor
     */
    public function Service() {
        return $this->service;
    }
} // class Flavor
