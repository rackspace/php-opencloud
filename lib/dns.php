<?php
/**
 * The Rackspace Cloud DNS service
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud;

require_once(__DIR__.'/domain.php');

class DNS extends Service {

	/**
	 */
	public function __construct(OpenStack $conn,
	        $serviceName, $serviceRegion, $urltype) {
		$this->debug(_('initializing DNS...'));
		parent::__construct(
			$conn,
			'rax:dns',
			$serviceName,
			$serviceRegion,
			$urltype
		);
	} // function __construct()

	/**
	 * Returns the selected endpoint URL of this Service
	 *
	 * @param string $resource - a child resource. For example,
	 *      passing 'servers' would return .../servers. Should *not* be
	 *    prefixed with a slash (/).
	 * @param array $args (optional) an array of key-value pairs for query
	 *      strings to append to the URL
	 * @returns string - the requested URL
	 */
	public function Url($resource='', $args=array()) {
	    $baseurl = parent::Url();
	    if ($resource != '')
	        $baseurl = noslash($baseurl).'/'.$resource;
	    if (!empty($args))
	        $baseurl .= '?'.$this->MakeQueryString($args);
		return $baseurl;
	}
	
	/**
	 */
	public function Domain($info=NULL) {
		return new DNS\Domain($this, $info);
	}

	/**
	 */
	public function DomainList($filter=array()) {
		$url = $this->Url(DNS\Domain::ResourceName(), $filter);
		return $this->Collection('\OpenCloud\DNS\Domain', $url);
	}

} // end class DNS