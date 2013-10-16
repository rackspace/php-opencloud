<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\ObjectStore\Resource;

use OpenCloud\Common\Lang;
use OpenCloud\Common\Exceptions;

/**
 * Objects are the basic storage entities in Cloud Files. They represent the 
 * files and their optional metadata you upload to the system. When you upload 
 * objects to Cloud Files, the data is stored as-is (without compression or 
 * encryption) and consists of a location (container), the object's name, and 
 * any metadata you assign consisting of key/value pairs.
 */
class DataObject extends AbstractStorageObject
{
    const HEADER_METADATA_PREFIX = 'X-Object-Meta-';
    const HEADER_METADATA_UNSET_PREFIX = 'X-Remove-Object-Meta-';

    public function __construct($container, $cdata = null)
    {
        parent::__construct();

        $this->container = $container;
   
        // For pseudo-directories, we need to ensure the name is set
        if (!empty($cdata->subdir)) {
            $this->setName($cdata->subdir)->setDirectory(true);
            return;
        } 
        
        $this->populate($cdata);
    }
    
    /**
     * @return bool Is this data object a pseudo-directory?
     */
    public function isDirectory()
    {
        return (bool) $this->directory;
    }
    
    public function primaryKeyField()
    {
        return 'name';
    }
    
    public function getUrl($path = null, array $params = array())
    {
        if (!$this->name) {
            throw new Exceptions\NoNameError(Lang::translate('Object has no name'));
        }

        return $this->container->getUrl(urlencode($this->name));
    }

    public function create($params = array(), $filename = null, $extractArchive = null)
    {
        
    }

    public function update($params = array(), $filename = '')
    {
        return $this->create($params, $filename);
    }

    /**
     * Updates metadata headers
     *
     * @api
     * @param array $params an optional associative array that can contain the
     *      'name' and 'type' of the object
     * @return boolean
     */
    public function updateMetadata($params = array())
    {
        $this->populate($params);

        // set the headers
        $headers = $this->metadataHeaders();
        $headers['Content-Type'] = $this->getContentType();

        return $this->getService()
            ->getClient()
            ->post($this->getUrl(), $headers)
            ->send();
    }

    /**
     * Deletes an object from the Object Store
     *
     * Note that we can delete without retrieving by specifying the name in the
     * parameter array.
     *
     * @api
     * @param array $params an array of parameters
     * @return HttpResponse if successful; FALSE if not
     * @throws DeleteError
     */
    public function delete($params = array())
    {
        $this->populate($params);
        return $this->getService()->getClient()->delete($this->getUrl())->send();
    }

    /**
     * Copies the object to another container/object
     *
     * Note that this function, because it operates within the Object Store
     * itself, is much faster than downloading the object and re-uploading it
     * to a new object.
     *
     * @param DataObject $target the target of the COPY command
     */
    public function copy(DataObject $target)
    {
        $uri = sprintf('/%s/%s', $target->container()->name(), $target->name());

        $this->getLogger()->info('Copying object to [{uri}]', array('uri' => $uri));

        $request = $this->getService()
            ->getClient()
            ->createRequest('COPY', $this->getUrl(), array(
                'Destination' => $uri
            ))
            ->setExpectedResponse(202);
        
        return $request->send();
    }

    /**
     * returns the TEMP_URL for the object
     *
     * Some notes:
     * * The `$secret` value is arbitrary; it must match the value set for
     *   the `X-Account-Meta-Temp-URL-Key` on the account level. This can be
     *   set by calling `$service->SetTempUrlSecret($secret)`.
     * * The `$expires` value is the number of seconds you want the temporary
     *   URL to be valid for. For example, use `60` to make it valid for a
     *   minute
     * * The `$method` must be either GET or PUT. No other methods are
     *   supported.
     *
     * @param string $secret the shared secret
     * @param integer $expires the expiration time (in seconds)
     * @param string $method either GET or PUT
     * @return string the temporary URL
     */
    public function tempUrl($secret, $expires, $method)
    {
        $method = strtoupper($method);
        $expiry_time = time() + $expires;

        // check for proper method
        if ($method != 'GET' && $method != 'PUT') {
            throw new Exceptions\TempUrlMethodError(sprintf(
                Lang::translate(
                'Bad method [%s] for TempUrl; only GET or PUT supported'),
                $method
            ));
        }

        // construct the URL
        $url  = $this->getUrl();
        $path = urldecode(parse_url($url, PHP_URL_PATH));

        $hmac_body = "$method\n$expiry_time\n$path";
        $hash = hash_hmac('sha1', $hmac_body, $secret);

        $this->getLogger()->info('URL [{url}]; SIG [{sig}]; HASH [{hash}]', array(
            'url'  => $url, 
            'sig'  => $hmac_body, 
            'hash' => $hash
        ));

        $temp_url = sprintf('%s?temp_url_sig=%s&temp_url_expires=%d', $url, $hash, $expiry_time);

        // debug that stuff
        $this->getLogger()->info('TempUrl generated [{url}]', array(
            'url' => $temp_url
        ));

        return $temp_url;
    }

    /**
     * Return object's data as a string
     *
     * @return string the entire object
     */
    public function saveToString()
    {
        return $this->getService()
            ->getClient()
            ->get($this->getUrl())
            ->send()
            ->getDecodedBody();
    }

    /**
     * Saves the object's data to local filename
     *
     * Given a local filename, the Object's data will be written to the newly
     * created file.
     *
     * Example:
     * <code>
     * # ... authentication/connection/container code excluded
     * # ... see previous examples
     *
     * # Whoops!  I deleted my local README, let me download/save it
     * #
     * $my_docs = $conn->get_container("documents");
     * $doc = $my_docs->get_object("README");
     *
     * $doc->SaveToFilename("/home/ej/cloudfiles/readme.restored");
     * </code>
     *
     * @param string $filename name of local file to write data to
     * @return boolean <kbd>TRUE</kbd> if successful
     * @throws IOException error opening file
     * @throws InvalidResponseException unexpected response
     */
    public function saveToFilename($filename)
    {
        if (!$fp = @fopen($filename, "wb")) {
            throw new Exceptions\IOError(sprintf(
                Lang::translate('Could not open file [%s] for writing'),
                $filename
            ));
        }
        
        $result = $this->getService()
            ->getClient()
            ->get($this->getUrl(), array(), $fp)
            ->send();
        
        fclose($fp);
        
        return $result;
    }

    /**
     * Saves the object's to a stream filename
     *
     * Given a local filename, the Object's data will be written to the stream
     *
     * Example:
     * <code>
     * # ... authentication/connection/container code excluded
     * # ... see previous examples
     *
     * # If I want to write the README to a temporary memory string I
     * # do :
     * #
     * $my_docs = $conn->get_container("documents");
     * $doc = $my_docs->DataObject(array("name"=>"README"));
     *
     * $fp = fopen('php://temp', 'r+');
     * $doc->SaveToStream($fp);
     * fclose($fp);
     * </code>
     *
     * @param string $filename name of local file to write data to
     * @return boolean <kbd>TRUE</kbd> if successful
     * @throws IOException error opening file
     * @throws InvalidResponseException unexpected response
     */
    public function saveToStream($resource)
    {
        if (!is_resource($resource)) {
            throw new Exceptions\ObjectError(
                Lang::translate("Resource argument not a valid PHP resource."
            ));
        }

        return $this->getService()
            ->getClient()
            ->get($this->getUrl(), array(), $resource)
            ->send();
    }

    /**
     * Purges the object from the CDN
     *
     * Note that the object will still be served up to the time of its
     * TTL value.
     *
     * @api
     * @param string $email An email address that will be notified when
     *      the object is purged.
     * @return void
     * @throws CdnError if the container is not CDN-enabled
     * @throws CdnHttpError if there is an HTTP error in the transaction
     */
    public function purgeCDN($email)
    {
        // @codeCoverageIgnoreStart
        if (!$cdn = $this->container()->CDNURL()) {
            throw new Exceptions\CdnError(Lang::translate('Container is not CDN-enabled'));
        }
        // @codeCoverageIgnoreEnd

        $url = $cdn . '/' . $this->name;
        $this->getService()
            ->getClient()
            ->delete($url, array('X-Purge-Email' => $email))
            ->send();
        
        return true;
    }

    /**
     * Returns the CDN URL (for managing the object)
     *
     * Note that the DataObject::PublicURL() method is used to return the
     * publicly-available URL of the object, while the CDNURL() is used
     * to manage the object.
     *
     * @return string
     */
    public function CDNURL()
    {
        return $this->container()->CDNURL() . '/' . $this->name;
    }

    /**
     * Returns the object's Public CDN URL, if available
     *
     * @api
     * @param string $type can be 'streaming', 'ssl', 'ios-streaming', 
     *		or anything else for the
     *      default URL. For example, `$object->PublicURL('ios-streaming')`
     * @return string
     */
    public function publicURL($type = null)
    {
        if (!$prefix = $this->container()->CDNURI()) {
            return null;
        }

        switch(strtoupper($type)) {
            case 'SSL':
                $url = $this->container()->SSLURI().'/'.$this->name;
                break;
            case 'STREAMING':
                $url = $this->container()->streamingURI().'/'.$this->name;
                break;
            case 'IOS':
            case 'IOS-STREAMING':
            	$url = $this->container()->iosStreamingURI().'/'.$this->name;
                break;
            default:
                $url = $prefix.'/'.$this->name;
                break;
        }
        
        return $url;
    }

    public function refresh()
    {
        if (!$this->name) {
            throw new Exceptions\NoNameError(Lang::translate('Cannot retrieve an unnamed object'));
        }

        $response = $this->getService()
            ->getClient()
            ->head($this->getUrl(), array('Accept' => '*/*'))
            ->send();

        // set headers as metadata?
        $this->saveResponseHeaders($response);

        // parse the metadata
        $this->getMetadata($response);
    }

}
