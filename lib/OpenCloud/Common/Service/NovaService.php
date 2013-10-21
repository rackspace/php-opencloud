<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Service;

use OpenCloud\OpenStack;
use OpenCloud\Common\Lang;
use OpenCloud\Compute\Resource\Flavor;

/**
 * Nova is an abstraction layer for the OpenStack compute service.
 *
 * Nova is used as a basis for several products, including Compute services
 * as well as Rackspace's Cloud Databases. This class is, in essence, a vehicle
 * for sharing common code between those other classes.
 */
abstract class NovaService extends AbstractService
{

	private $_url;

	/**
	 * Called when creating a new Compute service object
	 *
	 * _NOTE_ that the order of parameters for this is *different* from the
	 * parent Service class. This is because the earlier parameters are the
	 * ones that most typically change, whereas the later ones are not
	 * modified as often.
	 *
	 * @param \OpenCloud\Identity $conn - a connection object
	 * @param string $serviceRegion - identifies the region of this Compute
	 *      service
	 * @param string $urltype - identifies the URL type ("publicURL",
	 *      "privateURL")
	 * @param string $serviceName - identifies the name of the service in the
	 *      catalog
	 */
	public function __construct(
		OpenStack $conn,
	    $serviceType, 
	    $serviceName, 
	    $serviceRegion, 
	    $urltype
	) {
		parent::__construct(
			$conn,
			$serviceType,
			$serviceName,
			$serviceRegion,
			$urltype
		);
        
		$this->_url = Lang::noslash(parent::Url());
        
        $this->getLogger()->info(Lang::translate('Initializing Nova...'));
	}

	/**
	 * Returns a flavor from the service
	 *
	 * This is a factory method and should generally be called instead of
	 * creating a Flavor object directly.
	 *
	 * @api
	 * @param string $id - if supplied, the Flavor identified by this is
	 *      retrieved
	 * @return Compute\Flavor object
	 */
	public function flavor($id = null) 
	{
	    return new Flavor($this, $id);
	}

	/**
	 * Returns a list of Flavor objects
	 *
	 * This is a factory method and should generally be called instead of
	 * creating a FlavorList object directly.
	 *
	 * @api
	 * @param boolean $details - if TRUE (the default), returns full details.
	 *      Set to FALSE to retrieve minimal details and possibly improve
	 *      performance.
	 * @param array $filter - optional key/value pairs for creating query
	 *      strings
	 * @return Collection (or FALSE on an error)
	 */
	public function flavorList($details = true, array $filter = array()) 
	{
        $path = Flavor::resourceName();
        
        if ($details === true) {
            $path .= '/detail';
        }

        return $this->collection('OpenCloud\Compute\Resource\Flavor', $this->getUrl($path, $filter));
	}

	/**
	 * Loads the available namespaces from the /extensions resource
	 */
	protected function loadNamespaces() 
    {
	    foreach($this->extensions() as $object) {
	        $this->namespaces[] = $object->alias;
	    }
	}

}
