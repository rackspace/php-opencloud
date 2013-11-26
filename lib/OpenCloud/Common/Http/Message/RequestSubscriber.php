<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Common\Http\Message;

use Guzzle\Common\Event;
use Guzzle\Http\Message\EntityEnclosingRequest;
use OpenCloud\Common\Constants\Header;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Description of RequestSubscriber
 */
class RequestSubscriber implements EventSubscriberInterface
{
    
    public static function getInstance()
    {
        return new self();
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send'    => 'ensureContentType',
            'curl.callback.progress' => 'doCurlProgress'
        );
    }

    public function ensureContentType(Event $event)
    {
        if ($event['request'] instanceof EntityEnclosingRequest
            && $event['request']->getBody()
            && $event['request']->getBody()->getContentLength()
            && !$event['request']->hasHeader(Header::CONTENT_TYPE)
        ) {
            $event['request']->setHeader(Header::CONTENT_TYPE, 'application/json');
        }
    }

    /**
     * @param $options
     * @return mixed
     * @codeCoverageIgnore
     */
    public function doCurlProgress($options)
    {
        $curlOptions = $options['request']->getCurlOptions();
    	if ($curlOptions->hasKey('progressCallback')) {
	    	return call_user_func($curlOptions->get('progressCallback'));
    	} else {
	    	echo sprintf(
	    		"Download size: [%d]\nDownloaded: [%d]\nUpload size: [%d]\nUploaded: [%d]\n",
	    		$options['download_size'],
				$options['downloaded'],
            	$options['upload_size'],
            	$options['uploaded']
			);
    	}
    }
    
}