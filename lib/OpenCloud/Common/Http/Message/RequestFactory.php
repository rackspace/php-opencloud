<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http\Message;

use Guzzle\Http\Message\RequestFactory as GuzzleRequestFactory;

/**
 * Description of RequestFactory
 * 
 * @link 
 */
class RequestFactory extends GuzzleRequestFactory
{
    protected $requestClass = 'Request';
    protected $entityEnclosingRequestClass = 'EntityEnclosingRequest';
    
    public function create($method, $url, $headers = null, $body = null, array $options = array())
    {
        $method = strtoupper($method);

        if ($method == 'GET' || $method == 'HEAD' || $method == 'TRACE' || $method == 'OPTIONS') {
            // Handle non-entity-enclosing request methods
            $request = new Request($method, $url, $headers);
            if ($body) {
                // The body is where the response body will be stored
                $type = gettype($body);
                if ($type == 'string' || $type == 'resource' || $type == 'object') {
                    $request->setResponseBody($body);
                }
            }
        } else {
            // Create an entity enclosing request by default
            $request = new EntityEnclosingRequest($method, $url, $headers);
            if ($body) {
                // Add POST fields and files to an entity enclosing request if an array is used
                if (is_array($body) || $body instanceof Collection) {
                    // Normalize PHP style cURL uploads with a leading '@' symbol
                    foreach ($body as $key => $value) {
                        if (is_string($value) && substr($value, 0, 1) == '@') {
                            $request->addPostFile($key, $value);
                            unset($body[$key]);
                        }
                    }
                    // Add the fields if they are still present and not all files
                    $request->addPostFields($body);
                } else {
                    // Add a raw entity body body to the request
                    $request->setBody($body, (string) $request->getHeader('Content-Type'));
                    if ((string) $request->getHeader('Transfer-Encoding') == 'chunked') {
                        $request->removeHeader('Content-Length');
                    }
                }
            }
        }

        if ($options) {
            $this->applyOptions($request, $options);
        }

        return $request;
    }
    
}