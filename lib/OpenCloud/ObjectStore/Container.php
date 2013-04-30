<?php
/**
 * Containers for OpenStack Object Storage (Swift) and Rackspace Cloud Files
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @package phpOpenCloud
 * @version 1.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\ObjectStore;

use OpenCloud\Base\Exceptions;

/**
 * A regular container with a (potentially) CDN container
 *
 * This is the main entry point; CDN containers should only be used internally.
 */
class Container extends CDNContainer {

    private
        $_cdn;      // holds the related CDN container (if any)

	/**
	 * Makes the container public via the CDN
	 *
	 * @api
	 * @param integer $TTL the Time-To-Live for the CDN container; if NULL,
	 *      then the cloud's default value will be used for caching.
	 * @throws CDNNotAvailableError if CDN services are not available
	 * @return CDNContainer
	 */
	public function EnableCDN($TTL=NULL) {
	    $url = $this->Service()->CDN()->Url().'/'.$this->name;
	    $headers = $this->MetadataHeaders();
	    if ($TTL && !is_integer($TTL))
	        throw new \OpenCloud\Base\Exceptions\CdnTtlError(sprintf(
	            \OpenCloud\Base\Lang::translate('TTL value [%s] must be an integer'), $TTL));
	    if ($TTL)
	        $headers['X-TTL'] = $TTL;
	    $headers['X-Log-Retention'] = 'True';
	    $headers['X-CDN-Enabled'] = 'True';

	    // PUT to the CDN container
	    $response = $this->Service()->Request($url, 'PUT', $headers);

	    // check the response status
	    if ($response->HttpStatus() > 202)
	        throw new \OpenCloud\Base\Exceptions\CdnHttpError(sprintf(
	            \OpenCloud\Base\Lang::translate('HTTP error publishing to CDN, status [%d] response [%s]'),
	            $response->HttpStatus(), $response->HttpBody()));

	    // refresh the data
	    $this->Refresh();

	    // return the CDN container object
	    $this->_cdn = new CDNContainer($this->Service()->CDN(), $this->name);
	    return $this->CDN();
	}

	/**
	 * a synonym for PublishToCDN for backwards-compatibility
	 *
	 * @api
	 */
	public function PublishToCDN($TTL=NULL) {
		return $this->EnableCDN($TTL);
	}

	/**
	 * Disables the containers CDN function.
	 *
	 * Note that the container will still be available on the CDN until
	 * its TTL expires.
	 *
	 * @api
	 * @return void
	 */
	public function DisableCDN() {
	    $headers['X-Log-Retention'] = 'False';
	    $headers['X-CDN-Enabled'] = 'False';

	    // PUT it to the CDN service
	    $response = $this->Service()->Request($this->CDNURL(), 'PUT', $headers);

	    // check the response status
	    if ($response->HttpStatus() != 201)
	        throw new \OpenCloud\Base\Exceptions\CdnHttpError(sprintf(
	            \OpenCloud\Base\Lang::translate('HTTP error disabling CDN, status [%d] response [%s]'),
	            $response->HttpStatus(), $response->HttpBody()));
	}

	/**
	 * Creates a static website from the container
	 *
	 * @api
	 * @link http://docs.rackspace.com/files/api/v1/cf-devguide/content/Create_Static_Website-dle4000.html
	 * @param string $index the index page (starting page) of the website
	 * @return \OpenCloud\HttpResponse
	 */
	public function CreateStaticSite($index) {
		$headers = array('X-Container-Meta-Web-Index'=>$index);
		$response = $this->Service()->Request($this->Url(), 'POST', $headers);

		// check return code
		if ($response->HttpStatus() > 204)
			throw new \OpenCloud\Base\Exceptions\ContainerError(sprintf(
				\OpenCloud\Base\Lang::translate('Error creating static website for [%s], status [%d] response [%s]'),
				$this->name, $response->HttpStatus(), $response->HttpBody()));

		return $response;
	}

	/**
	 * Sets the error page(s) for the static website
	 *
	 * @api
	 * @link http://docs.rackspace.com/files/api/v1/cf-devguide/content/Set_Error_Pages_for_Static_Website-dle4005.html
	 * @param string $name the name of the error page
	 * @return \OpenCloud\HttpResponse
	 */
	public function StaticSiteErrorPage($name) {
		$headers = array('X-Container-Meta-Web-Error'=>$name);
		$response = $this->Service()->Request($this->Url(), 'POST', $headers);

		// check return code
		if ($response->HttpStatus() > 204)
			throw new \OpenCloud\Base\Exceptions\ContainerError(sprintf(
				\OpenCloud\Base\Lang::translate('Error creating static site error page for [%s], '.
				  'status [%d] response [%s]'),
				$this->name, $response->HttpStatus(), $response->HttpBody()));

		return $response;
	}

	/**
	 * Returns the CDN service associated with this container.
	 */
	public function CDN() {
	    $cdn = $this->_cdn;
	    if (!$cdn)
	        throw new \OpenCloud\Base\Exceptions\CdnNotAvailableError(\OpenCloud\Base\Lang::translate('CDN service is not available'));
	    return $cdn;
	}

	/**
	 * Returns the CDN URL of the container (if enabled)
	 *
	 * The CDNURL() is used to manage the container. Note that it is different
	 * from the PublicURL() of the container, which is the publicly-accessible
	 * URL on the network.
	 *
	 * @api
	 * @return string
	 */
	public function CDNURL() {
	    return $this->CDN()->Url();
	}

	/**
	 * Returns the Public URL of the container (on the CDN network)
	 *
	 */
	public function PublicURL() {
	    // return $this->CDNURI().'/'.$this->name;
	    return $this->CDNURI();
	}

	/**
	 * Returns the CDN info about the container
	 *
	 * @api
	 * @return stdClass
	 */
	public function CDNinfo($prop=NULL) {

	    // return NULL if the CDN container is not enabled
	    if (!isset($this->CDN()->metadata->Enabled))
	        return NULL;
	    if (!$this->CDN()->metadata->Enabled)
	        return NULL;

	    // check to see if it's set
	    if (isset($this->CDN()->metadata->$prop))
	        return trim($this->CDN()->metadata->$prop);
	    else if (isset($prop))
	        return NULL;

	    // otherwise, return the whole metadata object
	    return $this->CDN()->metadata;
	}

	/**
	 * Returns the CDN container URI prefix
	 *
	 * @api
	 * @return string
	 */
	public function CDNURI() {
	    return $this->CDNinfo('Uri');
	}

	/**
	 * Returns the SSL URI for the container
	 *
	 * @api
	 * @return string
	 */
	public function SSLURI() {
	    return $this->CDNinfo('Ssl-Uri');
	}

	/**
	 * Returns the streaming URI for the container
	 *
	 * @api
	 * @return string
	 */
	public function StreamingURI() {
	    return $this->CDNinfo('Streaming-Uri');
	}

    /**
     * Refreshes, then associates the CDN container
     */
    protected function Refresh() {
    	parent::Refresh();

        // find the CDN object
        $cdn = $this->Service()->CDN();
        if (isset($cdn)) {
            try {
                $this->_cdn = new CDNContainer(
                    $this->Service()->CDN(),
                    $this->name
                );
            } catch (\OpenCloud\Base\Exceptions\ContainerNotFoundError $e) {
                $this->_cdn = new CDNContainer($cdn);
                $this->_cdn->name = $this->name;
            }
        }
    }
} // class Container