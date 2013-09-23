<?php

/**
 * Unit Tests
 *
 * @copyright 2012-2013 Rackspace Hosting, Inc.
 * See COPYING for licensing information
 *
 * @version 1.0.0
 * @author Glen Campbell <glen.campbell@rackspace.com>
 */

namespace OpenCloud\Tests\Common;

use PHPUnit_Framework_TestCase;
use OpenCloud\Common\Base;
use OpenCloud\Common\Lang;

/**
 * Can't test Base directly, since it is an abstract class, so we instantiate it
 */
class MyBase extends Base
{

    public $foo; // to test SetProperty
    
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
    
    public function getHttpRequestObject($url, $method = 'GET', array $options = array())
    {
        return parent::GetHttpRequestObject($url, $method);
    }

}

class BaseTest extends PHPUnit_Framework_TestCase
{

    private $my;

    /**
     * Create our redirected Base class
     */
    public function __construct()
    {
        $this->my = new MyBase;
    }

    /**
     * Tests
     */
    public function test_gettext()
    {
        $this->assertEquals(Lang::translate('Hello'), 'Hello');
    }

    public function test_Incorrect_Method()
    {
        $this->assertNull($this->my->fooBarMethod());
    }
    
    public function test_Setting_NonExistent_Property()
    {
        $object = $this->my;
        
        $object->getLogger()
            ->setOption('outputToFile', false)
            ->setEnabled(true);
        
        $object->setGhost('foobar');
        
        $this->expectOutputRegex('#property has not been defined.#');
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
        $data = (object) array(
            'metadata' => array(
                'foo' => 'bar'
            )
        );
        $object->populate($data);
        
        $this->assertInstanceOf('OpenCloud\Common\Metadata', $object->getMetadata());
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\URLError
     */
    public function testUrl()
    {
        $this->my->Url();
    }

    public function testGetHttpRequestObject()
    {
        $request = $this->my->GetHttpRequestObject('file:/dev/null');
        $this->assertEquals(
            'OpenCloud\Common\Request\Curl', get_class($request));
    }

    public function testMakeQueryString()
    {
        $this->assertEquals(
            'A=1', $this->my->MakeQueryString(array('A' => 1)));
        $this->assertEquals(
            'A=1&B=2', $this->my->MakeQueryString(array('A' => 1, 'B' => 2)));
        $this->assertEquals(
            'A=1&B=False', $this->my->MakeQueryString(array('A' => 1, 'B' => FALSE)));
        $this->assertEquals(
            'A=1&B=True', $this->my->MakeQueryString(array('A' => 1, 'B' => TRUE)));
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\JsonError
     */
    public function testCheckJsonError()
    {
        $obj = json_decode('{"one":"two"}');
        $this->my->CheckJsonError();
        
        $obj = json_decode('{"one":"two"');
        $this->my->CheckJsonError();
    }

    public function testSetProperty()
    {
        $this->my->setBar('hello');
        $this->assertEquals('hello!!!', $this->my->getBar());
        
        $this->my->setBaz('goodbye');
        $this->assertEquals('goodbye', $this->my->getBaz());
    }

}
