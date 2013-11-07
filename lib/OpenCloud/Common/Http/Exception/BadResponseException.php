<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Common\Http\Exception;

use Guzzle\Http\Exception\BadResponseException as GuzzleBadResponseException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class BadResponseException extends GuzzleBadResponseException
{

    public static function factory(RequestInterface $request, Response $response)
    {
        if ($response->isClientError()) {
            $label = 'Client error response';
            $class = '\\Guzzle\\Http\\Exception\\ClientErrorResponseException';
        } elseif ($response->isServerError()) {
            $label = 'Server error response';
            $class = '\\Guzzle\\Http\\Exception\\ServerErrorResponseException';
        } else {
            $label = 'Unsuccessful response';
            $class = __CLASS__;
            $e = new self();
        }

        $message = $label . PHP_EOL . implode(PHP_EOL, array(
                '[status code] ' . $response->getStatusCode(),
                '[reason phrase] ' . $response->getReasonPhrase(),
                '[message] ' . (string) $response->getBody(),
                '[method] ' . $request->getMethod(),
                '[url] ' . $request->getUrl()
            ));

        $e = new $class($message);
        $e->setResponse($response);
        $e->setRequest($request);

        return $e;
    }

}