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

use Guzzle\Http\Message\RequestFactory as GuzzleRequestFactory;

/**
 * Description of RequestFactroy
 * 
 * @link 
 */
class RequestFactory extends GuzzleRequestFactory
{   
    protected $requestClass = 'OpenCloud\\Common\\Http\\Message\\Request';
    protected $entityEnclosingRequestClass = 'OpenCloud\\Common\\Http\\Message\\EntityEnclosingRequest';   
}