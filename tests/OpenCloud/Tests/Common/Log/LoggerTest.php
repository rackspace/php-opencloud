<?php
/**
 * @copyright Copyright 2012-2014 Rackspace US, Inc.
      See COPYING for licensing information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   1.5.9
 * @author    Glen Campbell <glen.campbell@rackspace.com>
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Common\Log;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Log\Logger;

class LoggerTest extends PHPUnit_Framework_TestCase 
{
    
    public function __construct()
    {
        $this->logger = new Logger;
        $this->logger->setEnabled(false);
    }
    
    public function testUrgency()
    {
        $this->logger->emergency('Emergency');
        $this->expectOutputRegex('/Emergency/');
        
        $this->logger->alert('Alert');
        $this->expectOutputRegex('/Alert/');
        
        $this->logger->critical('Critical');
        $this->expectOutputRegex('/Critical/');
    }
    
    public function testLogLevels()
    {
        $this->assertNull($this->logger->warning('Warning'));
        $this->assertNull($this->logger->error('Error'));
        $this->assertNull($this->logger->debug('Debug'));
        
        $this->logger->setEnabled(true);
        
        $this->logger->notice('Notice: {string}', array('string' => 'Hello world'));
        $this->expectOutputRegex('/Notice: Hello world/');
    }
    
    public function testSettingOptions()
    {
        $file = __DIR__ . '/test.log';
        
        $fp = fopen($file, 'w');
        
        $this->logger->setOptions(array(
            'outputToFile' => true,
            'logFile'      => $file,
            'foo'          => 'bar'
        ));
        
        $this->assertNull($this->logger->getOption('foo'));
        
        $this->logger->emergency('This is an emergency!');
        $this->assertRegExp('#This is an emergency!#', file_get_contents($file));
        
        fclose($fp);
        unlink($file);
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\LoggingException
     */
    public function testOutputFailsWithIncorrectFile()
    {
        $this->logger->setOptions(array(
            'outputToFile' => true,
            'logFile'      => __DIR__ . '/fakeFile'
        ));
        
        $this->logger->emergency('Can anyone see this?');
    }
    
}