<?php
/**
 * An abstraction that defines instances created under OpenStack Nova
 * (Servers and Cloud Database Instances)
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
require_once('metadata.inc');

/**
 * The NovaInstance class represents a single nova instance. This can be a Server, a
 * database instance, or another type of packaged product that uses a Nova instance as
 * its base.
 *
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
abstract class NovaInstance extends \OpenCloud\Base {

	protected
		$id,
		$_service;

	/**
	 * Retrieves the instance from persistent storage
	 *
	 * @param OpenCloud\Nova $nova the service object
	 * @param mixed $info the ID or array/object of data
	 * @throws InvalidArgumentError if $info has an invalid data type
	 */
	public function __construct(\OpenCloud\Nova $nova, $info=NULL) {
		$this->_service = $nova;
		if (is_string($info) || is_integer($info))
			$this->Refresh((string)$info);
		elseif (is_object($info) || is_array($info))
			foreach($info as $key => $value)
				$this->$key = $value;
		elseif (isset($info))
			throw new InvalidArgumentError(sprintf(
				_('Argument for %s must be string or object, [%s] used'),
				get_class($this),
				get_class($info)));
	}

	/**
	 * Returns the default URL of the object
	 *
	 * This may have to be overridden in subclasses.
	 *
	 * @return string
	 * @throws \OpenCloud\UrlError if URL is not defined
	 */
	public function Url($subresource=NULL) {
		// first, see if we have a [self] link
		$url = $this->FindLink('self');

		// next, check to see if we have an ID
		if (!$url && $this->id)
			$url = noslash($this->Service()->Url($this->ResourceName()))
			        . '/' . $this->id;

		// add the subresource
		if ($url)
			return $url . ($subresource ? ('/'.$subresource) : '');

		// otherwise, we don't have a URL yet
		throw new \OpenCloud\UrlError(
			sprintf(_('%s does not have a URL yet'), get_class($this)));
		return FALSE;
	}

	/**
	 * Waits for the server/instance status to change
	 *
	 * This function repeatedly polls the system for a change in server
	 * status. Once the status reaches the `$terminal` value (or 'ERROR'),
	 * then the function returns.
	 *
	 * The polling interval is set by the constant RAXSDK_POLL_INTERVAL.
	 *
	 * The function will automatically terminate after RAXSDK_SERVER_MAXTIMEOUT
	 * seconds elapse.
	 *
	 * @api
	 * @param string $terminal the terminal state to wait for
	 * @param integer $timeout the max time (in seconds) to wait
	 * @param callable $callback a callback function that is invoked with
	 *      each repetition of the polling sequence. This can be used, for
	 *      example, to update a status display or to permit other operations
	 *      to continue
	 * @return void
	 *
	 */
	public function WaitFor($terminal='ACTIVE',
	        $timeout=RAXSDK_SERVER_MAXTIMEOUT, $callback=NULL) {
		$starttime = time();
		$startstatus = $this->status;
		while (TRUE) {
			$this->Refresh($this->id);
			if ($callback)
				call_user_func($callback, $this);
			if ($this->status == 'ERROR') return;
			if ($this->status == $terminal) return;
			if (time()-$starttime > $timeout) return;
			sleep(RAXSDK_POLL_INTERVAL);
		}
	}

	/**
	 * Returns the Service object associated with this server
	 */
	public function Service() {
		return $this->_service;
	}

	/**
	 * Validates properties that have a namespace: prefix
	 *
	 * If the property prefix: appears in the list of supported extension
	 * namespaces, then the property is applied to the object. Otherwise,
	 * an exception is thrown.
	 *
	 * @param string $name the name of the property
	 * @param mixed $value the property's value
	 * @return void
	 * @throws \OpenCloud\AttributeError
	 */
    public function __set($name, $value) {
        $this->SetProperty($name, $value, $this->Service()->namespaces());
    }

	/**
	 * Refreshes the server from the origin (useful when the server is
	 * changing states)
	 *
	 * @return void
	 * @throws ServerIdRequiredError
	 */
	public function Refresh($id=NULL) {
		// error if no ID
		if (!isset($id))
			$id = $this->id;
		if (!$id)
			throw new ServerIdRequiredError(sprintf(
			    _('%s has no ID; cannot be refreshed'), get_class($this)));

		// retrieve it
		$this->debug(_('%s id [%s]'), get_class($this), $id);
		$this->id = $id;
		$url = $this->Url();
		$response = $this->Service()->Request($url);

		// check status codes
		if ($response->HttpStatus() == 404)
			throw new InstanceNotFound(
				sprintf(_('%s [%s] not found [%s]'),
					get_class($this), $this->id, $url));

		if ($response->HttpStatus() >= 300)
			throw new UnknownError(
				sprintf(_('Unexpected %s error [%d] [%s]'),
					get_class($this),
					$response->HttpStatus(),
					$response->HttpBody()));

		// we're ok, reload the response
		if ($json = $response->HttpBody()) {
			$this->debug('Refresh() JSON [%s]', $json);
			$resp = json_decode($json);
			if ($this->CheckJsonError())
				throw new ServerJsonError(sprintf(
				    _('JSON parse error on %s refresh'), get_class($this)));
			$top = $this->JsonName();
			foreach($resp->$top as $item => $value) {
				$this->$item = $value;
			}
		}
	}

	/********** PROTECTED METHODS **********/

	/**
	 * Sends the json string to the /action resource
	 *
	 * This is used for many purposes, such as rebooting the server,
	 * setting the root password, creating images, etc.
	 * Since it can only be used on a live server, it checks for a valid ID.
	 *
	 * @param $object - this will be encoded as json, and we handle all the JSON
	 *     error-checking in one place
	 * @throws ServerIdError if server ID is not defined
	 * @throws ServerActionError on other errors
	 * @returns boolean; TRUE if successful, FALSE otherwise
	 */
	protected function Action($object) {
		// we always require a valid ID
		if (!$this->id)
			throw new ServerIdRequiredError(sprintf(
				_('%s is not defined'), get_class($this)));

		// verify that it is an object
		if (!is_object($object))
		    throw new ServerActionError(sprintf(
		        _('%s::Action() requires an object as its parameter'),
		        get_class($this)));

		// convert the object to json
		$json = json_encode($object);
		$this->debug('JSON [%s]', $json);
		if ($this->CheckJsonError())
			return FALSE;

		// debug - save the request
		$this->debug(_('%s::Action [%s]'), get_class($this), $json);

		// get the URL for the POST message
		$url = $this->Url('action');

		// POST the message
		$response = $this->Service()->Request(
			$url,
			'POST',
			array(),
			$json
		);
		if (!is_object($response))
			throw new \OpenCloud\HttpError(sprintf(
			    _('Invalid response for %s::Action() request'),
			    get_class($this)));

		// check for errors
		if ($response->HttpStatus() >= 300) {
			$obj = json_decode($response->HttpBody());
			throw new ServerActionError(
				sprintf(_('%s::Action() [%s] failed; response [%s]'),
					get_class($this), $url, $response->HttpBody()));
		}

		return $response;
	}

	/**
	 * Since each server can have multiple links, this returns the desired one
	 *
	 * @param string $type - 'self' is most common; use 'bookmark' for
	 *      the version-independent one
	 * @return string the URL from the links block
	 */
	protected function FindLink($type='self') {
		if (!isset($this->links))
			return FALSE;
		elseif (!$this->links)
			return FALSE;
		foreach ($this->links as $link) {
			if ($link->rel == $type)
				return $link->href;
		}
		return FALSE;
	}

	/**
	 * Returns the top-level document identifier; must be overridden in child classes
	 *
	 * For example, a server document is (JSON) `{"server": ...}` and an Instance
	 * document is `{"instance": ...}` - this function must return the top level
	 * document name.
	 *
	 * @throws \OpenCloud\DocumentError if not overridden
	 */
	protected function JsonName() {
		throw new \OpenCloud\DocumentError(sprintf(
			_('Unrecognized class [%s] in JsonName() function'),
			get_class($this)));
	}

	/**
	 * Returns the resource name for the URL of the object; must be overridden in child
	 * classes
	 *
	 * For example, a server is /servers/, a database instance is /instances/.
	 * Must be overridden in child classes.
	 *
	 * @throws \OpenCloud\UrlError
	 */
	protected function ResourceName() {
		throw new \OpenCloud\UrlError(sprintf(
			_('Unrecognized class [%s] in ResourceName() function'),
			get_class($this)));
	}

}
