<?php

namespace OpenCloud\Base\Request;

use OpenCloud\Base\Base;
use OpenCloud\Base\Request\HttpRequestInterface;

/**
 * The CurlRequest class is a simple wrapper to CURL functions. Not only does
 * this permit stubbing of the interface as described under the HttpRequest
 * interface, it could potentially allow us to replace the interface methods
 * with other function calls in the future.
 *
 * @api
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */
class Curl extends Base implements HttpRequestInterface {

	private
		$url,
		$method,
		$handle,
		$retries=0,
		$headers=array(),		// headers sent with the request
		$returnheaders=array(); // headers returned from the request

    /**
     * initializes the CURL handle and HTTP method
     *
     * The constructor also sets a number of default values for options.
     *
     * @param string $url the URL to connect to
     * @param string $method the HTTP method (default "GET")
     * @param array $options optional hashed array of options => value pairs
     */
	public function __construct($url, $method='GET', $options=array()) {
		$this->url = $url;
		$this->method = $method;
		$this->handle = curl_init($url);

		// set our options
        $this->SetOption(CURLOPT_CUSTOMREQUEST, $method);
        foreach($options as $opt => $value) {
        	$this->debug(\OpenCloud\Base\Lang::translate('Setting option %s=%s'), $opt, $value);
        	$this->SetOption($opt, $value);
        }

        // set security handling options
        if (RAXSDK_SSL_VERIFYHOST != 2) {
            syslog(LOG_WARNING, \OpenCloud\Base\Lang::translate("WARNING: RAXSDK_SSL_VERIFYHOST has reduced security, value [" . RAXSDK_SSL_VERIFYHOST . "]\n"));
        }
        if (RAXSDK_SSL_VERIFYPEER !== TRUE) {
            syslog(LOG_WARNING, \OpenCloud\Base\Lang::translate("WARNING: RAXSDK_SSL_VERIFYPEER has reduced security\n"));
        }
        $this->SetOption(CURLOPT_SSL_VERIFYHOST, RAXSDK_SSL_VERIFYHOST);
        $this->SetOption(CURLOPT_SSL_VERIFYPEER, RAXSDK_SSL_VERIFYPEER);
        if (defined('RAXSDK_CACERTPEM') && file_exists(RAXSDK_CACERTPEM)) {
            $this->setOption(CURLOPT_CAINFO, RAXSDK_CACERTPEM);
        }

        //  curl code [18]
        //	message [transfer closed with x bytes remaining to read]
        if ($method === 'HEAD')
        	$this->SetOption(CURLOPT_NOBODY, TRUE);

        // follow redirects
        $this->SetOption(CURLOPT_FOLLOWLOCATION, TRUE);

        // don't return the headers in the request
		$this->SetOption(CURLOPT_HEADER, FALSE);

        // retrieve headers via callback
        $this->SetOption(CURLOPT_HEADERFUNCTION,
        	array($this, '_get_header_cb'));

		// return the entire request on curl_exec()
        $this->SetOption(CURLOPT_RETURNTRANSFER, TRUE);

        // uncomment to turn on Verbose mode
        //$http->SetOption(CURLOPT_VERBOSE, TRUE);

        // set default timeouts
        $this->SetConnectTimeout(RAXSDK_CONNECTTIMEOUT);
        $this->SetHttpTimeout(RAXSDK_TIMEOUT);
	}

	/**
	 * Sets a CURL option
	 *
	 * @param const $name - a CURL named constant; e.g. CURLOPT_TIMEOUT
	 * @param mixed $value - the value for the option
	 */
	public function SetOption($name, $value) {
		return curl_setopt($this->handle, $name, $value);
	}

	/**
	 * Explicit method for setting the connect timeout
	 *
	 * The connect timeout is the time it takes for the initial connection
	 * request to be established. It is different than the HTTP timeout, which
	 * is the time for the entire request to be serviced.
	 *
	 * @param integer $value The connection timeout in seconds.
	 *      Use 0 to wait indefinitely (NOT recommended)
	 */
	public function SetConnectTimeout($value) {
        $this->SetOption(CURLOPT_CONNECTTIMEOUT, $value);
	}

	/**
	 * Explicit method for setting the HTTP timeout
	 *
	 * The HTTP timeout is the time it takes for the HTTP request to be
	 * serviced. This value is usually larger than the connect timeout
	 * value.
	 *
	 * @param integer $value - the number of seconds to wait before timing out
	 *      the HTTP request.
	 */
	public function SetHttpTimeout($value) {
        $this->SetOption(CURLOPT_TIMEOUT, $value);
	}

	/**
	 * Sets the number of retries
	 *
	 * If you set this to a non-zero value, then it will repeat the request
	 * up to that number.
	 */
	public function SetRetries($value) {
	    $this->retries = $value;
	}

	/**
	 * Simplified method for setting lots of headers at once
	 *
	 * This method takes an associative array of header/value pairs and calls
	 * the setheader() method on each of them.
	 *
	 * @param array $arr an associative array of headers
	 */
	public function setheaders($arr) {
		if (!is_array($arr))
			throw new \OpenCloud\Base\Exceptions\HttpException(
				\OpenCloud\Base\Lang::translate('Value passed to CurlRequest::setheaders() must be array'));
		foreach ($arr as $name=>$value)
			$this->SetHeader($name, $value);
	}

	/**
	 * Sets a single header
	 *
	 * For example, to set the content type to JSON:
	 * `$request->SetHeader('Content-Type','application/json');`
	 *
	 * @param string $name The name of the header
	 * @param mixed $value The value of the header
	 */
	public function SetHeader($name, $value) {
		$this->headers[$name] = $value;
	}

	/**
	 * Executes the current request
	 *
	 * This method actually performs the request using the values set
	 * previously. It throws a OpenCloud\HttpError exception on
	 * any CURL error.
	 *
	 * @return OpenCloud\HttpResponse
	 * @throws OpenCloud\HttpError
	 */
	public function Execute() {
		// set all the headers
		$headarr = array();
		foreach ($this->headers as $name => $value)
			$headarr[] = $name.': '.$value;
        $this->SetOption(CURLOPT_HTTPHEADER, $headarr);

        // set up to retry if necessary
        $try_counter = 0;
        do {
		    $data = curl_exec($this->handle);
		    if (curl_errno($this->handle)&&($try_counter<$this->retries))
		        $this->debug(\OpenCloud\Base\Lang::translate('Curl error [%d]; retrying [%s]'),
		                curl_errno($this->handle), $this->url);
		} while((++$try_counter<=$this->retries) &&
		        (curl_errno($this->handle)!=0));

		// log retries error
		if ($this->retries && curl_errno($this->handle))
		    throw new \OpenCloud\Base\Exceptions\HttpRetryError(
		        sprintf(\OpenCloud\Base\Lang::translate('No more retries available, last error [%d]'),
		            curl_errno($this->handle)));

		// check for CURL errors
        switch(curl_errno($this->handle)) {
        case 0:     // everything's ok
            break;
        case 3:
            throw new \OpenCloud\Base\Exceptions\HttpUrlError(
                sprintf(\OpenCloud\Base\Lang::translate('Malformed URL [%s]'), $this->url));
            break;
        case 28:    // timeout
            throw new \OpenCloud\Base\Exceptions\HttpTimeoutError(
                \OpenCloud\Base\Lang::translate('Operation timed out; check RAXSDK_TIMEOUT value'));
            break;
        default:
            throw new \OpenCloud\Base\Exceptions\HttpError(
                sprintf(
                    \OpenCloud\Base\Lang::translate('HTTP error on [%s], curl code [%d] message [%s]'),
                    $this->url,
                    curl_errno($this->handle),
                    curl_error($this->handle)));
        }

		// otherwise, return the HttpResponse
		return new Response\Http($this, $data);
	}

	/**
	 * returns an array of information about the request
	 */
	public function info() {
		return curl_getinfo($this->handle);
	}

	/**
	 * returns the most recent CURL error number
	 */
	public function errno() {
		return curl_errno($this->handle);
	}

	/**
	 * returns the most recent CURL error string
	 */
	public function error() {
		return curl_error($this->handle);
	}

	/**
	 * Closes the HTTP request
	 */
	public function close() {
		return curl_close($this->handle);
	}

	/**
	 * Returns the headers as an array
	 */
	public function ReturnHeaders() {
		return $this->returnheaders;
	}

	/**
	 * This is a callback method used to handle the returned HTTP headers
	 *
	 * @param mixed $ch a CURL handle
	 * @param string $header the header string in its entirety
	 */
	public function _get_header_cb($ch, $header) {
		$this->returnheaders[] = $header;
		return strlen($header);
	}

} // class CurlRequest