<?php
/**
 * An abstract class that defines shared code among all Object Storage
 * components
 *
 * @copyright 2012 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

/**
 * Intermediate class to implement share features of all object-storage classes
 *
 * This class implements metadata-handling and other features that are common to
 * the object storage components.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
abstract class ObjStoreBase extends \OpenCloud\Base {

	const
		ACCOUNT_META_PREFIX = 'X-Account-',
		CONTAINER_META_PREFIX = 'X-Container-Meta-',
		OBJECT_META_PREFIX = 'X-Object-Meta-',
		CDNCONTAINER_META_PREFIX = 'X-Cdn-';

	public
		$metadata;

	/**
	 * initializes the metadata component
	 */
	public function __construct() {
	    $this->metadata = new \OpenCloud\Metadata();
	}

	/**
	 * Given an HttpResponse object, converts the appropriate headers
	 * to metadata
	 *
	 * @param \OpenCloud\HttpResponse
	 * @return void
	 */
	public function GetMetadata(\OpenCloud\HttpResponse $response) {
	    $this->metadata = new \OpenCloud\Metadata();
	    $prefix = $this->Prefix();
	    $this->metadata->SetArray($response->Headers(), $prefix);
	    /*
		foreach($response->Headers() as $header => $value) {
			$prefix = $this->prefix();
			if (strpos($header, $prefix) === 0) {
				$name = substr($header, strlen($prefix));
				$this->metadata[$name] = $value;
			}
		}
		*/
	}

	/**
	 * If object has metadata, return an associative array of headers
	 *
	 * For example, if a DataObject has a metadata item named 'FOO',
	 * then this would return array('X-Object-Meta-FOO'=>$value);
	 *
	 * @return array;
	 */
	public function MetadataHeaders() {
	    $headers = array();
	    $prefix = $this->Prefix();

	    // only build if we have metadata
	    if (is_object($this->metadata)) {
	        foreach($this->metadata as $key => $value)
	            $headers[$prefix.$key] = $value;
	    }

	    return $headers;
	}

	/********** PRIVATE METHODS **********/

	/**
	 */
	private function Prefix($type=NULL) {
		if (!isset($type)) {
			$parts = preg_split('/\\\/', get_class($this));
			$type = $parts[count($parts)-1];
		}
		switch($type) {
		case 'Account':
			return self::ACCOUNT_META_PREFIX;
		case 'CDNContainer':
		    return self::CDNCONTAINER_META_PREFIX;
		case 'Container':
			return self::CONTAINER_META_PREFIX;
		case 'DataObject':
			return self::OBJECT_META_PREFIX;
		default:
			throw new MetadataPrefixError(sprintf(
				_('Unrecognized metadata type [%s]'), $type));
		}
	}
}
