<?php
/**
 * Defines a DNS domain
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\DNS;

require_once(__DIR__.'/persistentobject.php');

/**
 * The Domain class represents a single domain
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Domain extends \OpenCloud\PersistentObject {

	public
		$id,
		$accountId,
		$ttl,
		$updated,
		$emailAddress,
		$created,
		$name,
		$comment;
	
	protected static
		$json_name = '',
		$json_collection_name = 'domains',
		$url_resource = 'domains';

	private
		$_create_keys = array(
		    'name',
			'emailAddress',
			'ttl',
			'comment'
		);

	/********** PROTECTED METHODS **********/

	/**
	 * Creates the JSON object for the Create() method
	 *
	 * @return stdClass
	 */
	protected function CreateJson() {
		$element = $this->JsonName();
		$obj = new \stdClass();
		$obj->$element = new \stdClass();
		foreach ($this->_create_keys as $name) {
			if ($this->$name) {
			    switch($name) {
			    case 'volume_type':
			        $obj->$element->$name = $this->volume_type->Name();
			        break;
			    default:
				    $obj->$element->$name = $this->$name;
				}
			}
		}
		if (is_array($this->metadata) && count($this->metadata)) {
			$obj->$element->metadata = new \stdClass();
			foreach($this->metadata as $key => $value)
				$obj->$element->metadata->$key = $value;
		}
		return $obj;
	}

} // class Volume
