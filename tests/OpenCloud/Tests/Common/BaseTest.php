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

namespace OpenCloud\Tests\Common;

use OpenCloud\Common\Base;
use OpenCloud\Common\Lang;

class MyBase extends Base
{
    public $foo;
    protected $bar;
    private $baz;
    private $metadata;

    public function setBar($bar)
    {
        $this->bar = $bar . '!!!';
    }

    public function getBar()
    {
        return $this->bar;
    }

    public static function getPatchHeaders()
    {
        return parent::getPatchHeaders();
    }
}

class BaseTest extends \OpenCloud\Tests\OpenCloudTestCase
{
    private $my;

    public function setupObjects()
    {
        $this->my = new MyBase;
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\RuntimeException
     */
    public function test_Incorrect_Method()
    {
        $this->assertNull($this->my->fooBarMethod());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\RuntimeException
     */
    public function test_Setting_NonExistent_Property()
    {
        $object = $this->my;
        $object->setGhost('foobar');
    }

    public function test_noslash()
    {
        $this->assertEquals(Lang::noslash('String/'), 'String');
        $this->assertEquals(Lang::noslash('String'), 'String');
    }

    public function testDebug()
    {
        $logger = $this->my->getLogger();
        $logger->setEnabled(true);

        $logger->info("HELLO, WORLD!");
        $this->expectOutputRegex('/ELLO/');
    }

    public function test_Metadata_Populate()
    {
        $object = $this->my;
        $data = (object)array(
            'metadata' => array(
                'foo' => 'bar'
            )
        );
        $object->populate($data);

        $this->assertInstanceOf('OpenCloud\Common\Metadata', $object->getMetadata());
    }

    public function testSetProperty()
    {
        $this->my->setBar('hello');
        $this->assertEquals('hello!!!', $this->my->getBar());

        $this->my->setBaz('goodbye');
        $this->assertEquals('goodbye', $this->my->getBaz());
    }

    public function testGetPatchHeaders()
    {
        $expectedHeaders = array(
            'Content-Type' => 'application/json-patch+json'
        );

        $my = $this->my;
        $this->assertEquals($expectedHeaders, $my::getPatchHeaders());
    }
}
