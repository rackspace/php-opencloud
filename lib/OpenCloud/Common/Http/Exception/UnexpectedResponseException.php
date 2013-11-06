<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http\Exception;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;

class UnexpectedResponseException extends BadResponseException
{
}