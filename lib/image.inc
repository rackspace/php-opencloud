<?php
/**
 * An object that defines a virtual machine image
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
 * The Image class represents a stored machine image returned by the
 * Compute service.
 *
 * In the future, this may be abstracted to access
 * Glance (the OpenStack image store) directly, but it is currently
 * not available to Rackspace customers, so we're using the /images
 * resource on the servers API endpoint.
 */
class Image extends \OpenCloud\Base {
    public
		$status,
		$updated,
		$links,
		$minDisk,
		$id,
		$name,
		$created,
		$progress,
		$minRam,
		$metadata,
		$server;
    private
        $service,   // the compute service associated with this image
        $imageurl;  // the URL of this image

    /**
     * Creates a new image object; if ID is specified, then the
     * image with the specified ID is retrieved.
     *
     * @param $id - the ID of the image to retrieve; otherwise, an empty
     *    Image object is created.
     * @throws ImageError, JsonError
     */
    public function __construct($compute, $id=NULL) {
        $this->service = $compute;
        $this->debug(('initializing Image [%s]'), $id);

        // set the metadata object
        $this->metadata = new \OpenCloud\Metadata();

        // if ID is specified, retrieve it
        if (is_array($id) || is_object($id)) {
        	foreach($id as $name => $value)
        		$this->$name = $value;
        }
        elseif ($id) {
            $this->imageurl = $this->service->Url('images/'.$id);
            $response = $this->service->Request($this->imageurl);
            if ($response->HttpStatus() >= 300)
                throw new ImageError(
                	sprintf(_('Unable to retrieve image [%s] status [%d]'),
                		$id, $response->HttpStatus()));
            else if (!$response->HttpBody())
                throw new EmptyResponseError(
                    sprintf(_('Empty result for image [%s]'), $id));
            else {
                $obj = json_decode($response->HttpBody());
                if ($this->CheckJsonError())
                	return FALSE;
                if (!isset($obj->image))
                    throw new ImageError(
                        sprintf(_('No image element in JSON for image [%s]'),
                            $id));
                else
                    foreach($obj->image as $property => $value) {
                        if ($property == 'metadata')
                            $this->metadata->SetArray($value);
                        else
                            $this->$property = $value;
                    }
            }
        }
    }

    /**
     * Validates extension attributes
     */
    public function __set($name, $value) {
        $this->SetProperty($name, $value, $this->Service()->namespaces());
    }

    /**
     * Returns the associated Service object
     */
    public function Service() {
        return $this->service;
    }

} // class Image
