<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests;

use Guzzle\Common\Event;
use OpenCloud\Common\Http\Message\Request;
use OpenCloud\Common\Http\Message\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MockSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => array(
                array('onBeforeSend', -1000)
            )
        );
    }

    public function onBeforeSend(Event $event)
    {
        if (strpos($event['request']->getUrl(), 'tokens') !== false) {
            // auth request must pass
            $message  = file_get_contents(__DIR__ . '/_response/Auth.resp');
            $response = Response::fromMessage($message);
            $event['request']->setResponse($response)->setState(Request::STATE_COMPLETE);
        } else {
            // default fallback is a 404
            $response = new Response(404);
            $event['request']->setResponse($response)->setState(Request::STATE_COMPLETE);
        }
    }

} 