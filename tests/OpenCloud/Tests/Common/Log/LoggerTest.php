<?php
/**
 * Copyright 2012-2014 Rackspace US, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace OpenCloud\Tests\Common\Log;

use OpenCloud\Common\Log\Logger;
use PHPUnit_Framework_TestCase;

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

    public function testDeprecationMessage()
    {
        $this->assertEquals(
            'The OpenCloud\Tests\Common\Log\LoggerTest::testDeprecationMessage method is deprecated, please use testMethod instead',
            $this->logger->deprecated(__METHOD__, 'testMethod')
        );
    }
}
