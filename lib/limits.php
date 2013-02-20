<?php
/**
 * Defines a DNS set of limits
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\DNS;

require_once(__DIR__.'/dnsobject.php');

/**
 */
class Limits extends DnsObject {

	const
		NOTYPE = 'NA';

	public
		$type=self::NOTYPE,
		$rate,
		$absolute;

	protected static
		$json_name = 'limits',
		$json_collection_name = FALSE,
		$url_resource = 'limits';

	/**
	 * creates the Limits object
	 *
	 * The "type" parameter can be used to specify a specific type of limit
	 *
	 * @param \OpenCloud\DNS $dns the parent DNS object
	 * @param string $type the type of limit
	 * @return void
	 */
	public function __construct(\OpenCloud\DNS $dns, $type=NULL) {
		if (isset($type))
			$this->type = $type;
		parent::__construct($dns, array('type'=>$this->type));
		$this->Refresh();
	}

	/**
	 * returns the URL of the limit
	 *
	 * handles the semaphore for NOTYPE
	 *
	 * @param string $subresource a subresource
	 * @param array $parm optional parameters (Key/value pairs)
	 * @return string;
	 */
	public function Url($subresource=NULL, $parm=NULL) {
		if ($this->type == self::NOTYPE) {
			$url = $this->Service()->Url(self::ResourceName(), $parm);
		}
		else
			$url = parent::Url($subresource, $parm);
		return $url;
	}

	/**
	 * no Create
	 */
	public function Create($params=array()) { $this->NoCreate(); }

	/**
	 * no Update
	 */
	public function Update($params=array()) { $this->NoUpdate(); }

	/**
	 * no Delete
	 */
	public function Delete() { $this->NoDelete(); }

	/**
	 * The "primary key field" is the $since parameter
	 */
	public function PrimaryKeyField() {
		return 'type';
	}

} // class Limit