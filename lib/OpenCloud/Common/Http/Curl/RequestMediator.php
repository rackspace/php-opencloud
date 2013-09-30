<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http\Curl;

use Guzzle\Http\Curl\RequestMediator as GuzzleRequestMediator;
use Guzzle\Http\EntityBody;
use OpenCloud\Common\Http\Message\Response;

class RequestMediator extends GuzzleRequestMediator
{
    public function receiveResponseHeader($curl, $header)
    {
        static $normalize = array("\r", "\n");
        $length = strlen($header);
        $header = str_replace($normalize, '', $header);

        if (strpos($header, 'HTTP/') === 0) {

            $startLine = explode(' ', $header, 3);
            $code = $startLine[1];
            $status = isset($startLine[2]) ? $startLine[2] : '';

            // Only download the body of the response to the specified response
            // body when a successful response is received.
            if ($code >= 200 && $code < 300) {
                $body = $this->request->getResponseBody();
            } else {
                $body = EntityBody::factory();
            }

            $response = new Response($code, null, $body);
            $response->setStatus($code, $status);
            $this->request->startResponse($response);

            $this->request->dispatch('request.receive.status_line', array(
                'request'       => $this,
                'line'          => $header,
                'status_code'   => $code,
                'reason_phrase' => $status
            ));

        } elseif ($pos = strpos($header, ':')) {
            $this->request->getResponse()->addHeader(
                trim(substr($header, 0, $pos)),
                trim(substr($header, $pos + 1))
            );
        }

        return $length;
    }
}