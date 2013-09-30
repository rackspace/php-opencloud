<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2013 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Common\Http;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Curl\CurlVersion;

/**
 * Description of Client
 * 
 * @link 
 */ 
class Client extends GuzzleClient
{
    
    const MINIMUM_PHP_VERSION = '5.3.3';
    
    public function __construct($url, $options)
    {
        // @codeCoverageIgnoreStart
    	if (PHP_VERSION < self::MINIMUM_PHP_VERSION) {
    		throw new Exceptions\UnsupportedVersionError(sprintf(
                Lang::translate('You must have PHP version >= %s installed.'),
                self::MINIMUM_PHP_VERSION
            ));
        }
        // @codeCoverageIgnoreEnd
        
        parent::__construct($url, $options);
    }
    
    public function getDefaultUserAgent()
    {
        return 'OpenCloud/' . self::VERSION
            . ' curl/' . CurlVersion::getInstance()->get('version')
            . ' PHP/' . PHP_VERSION;
    }
    
}