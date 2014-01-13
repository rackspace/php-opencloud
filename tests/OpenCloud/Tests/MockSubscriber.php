<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests;

use Guzzle\Common\Event;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MockSubscriber extends MockPlugin implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => array(
                array('onRequestBeforeSend', -999),
                array('onBeforeSendFallback', -1000)
            )
        );
    }

    public function onBeforeSendFallback(Event $event)
    {
        if (strpos($event['request']->getUrl(), 'tokens') !== false) {
            // auth request must pass
            $message  = file_get_contents(__DIR__ . '/_response/Auth.resp');
            $response = Response::fromMessage($message);
            $event['request']->setResponse($response)->setState(Request::STATE_COMPLETE);
            $event->stopPropagation();
        } else {
            // default fallback is a 404
            $response = new Response(200);
            $event['request']->setResponse($response)->setState(Request::STATE_COMPLETE);
            $event->stopPropagation();
        }
    }

    public function onRequestBeforeSend(Event $event)
    {
        if ($this->queue) {
            $request = $event['request'];
            $this->received[] = $request;
            // Detach the filter from the client so it's a one-time use
            if ($this->temporary && count($this->queue) == 1 && $request->getClient()) {
                $request->getClient()->getEventDispatcher()->removeSubscriber($this);
            }
            $this->dequeue($request);
            $event->stopPropagation();
        }
    }

} 