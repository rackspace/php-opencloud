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
use OpenCloud\Common\Collection;

class Gertrude
{

    public function foobar($item)
    {
        $object = new \stdClass;
        if (is_array($item) || is_object($item)) {
            foreach ($item as $k => $v) {
                $object->$k = $v;
            }
        } else {
            $object->id = $item;
        }
        return $object;
    }
    
}

class Ethel
{
    public function resource($name, $data)
    {
        return true;
    }
}

class CollectionTest extends PHPUnit_Framework_TestCase
{

    private $my;

    public function __construct()
    {
        $x = new Gertrude;
        $this->my = new Collection(
            $x, 'foobar', array(
                (object) array('id' => 'one', 'val' => 5),
                (object) array('id' => 'two', 'val' => 5),
                (object) array('id' => 'three', 'val' => 9),
                (object) array('id' => 'four', 'val' => 0),
            )
        );
    }

    public function test_Service()
    {
        $this->assertInstanceOf('OpenCloud\Tests\Common\Gertrude', $this->my->getService());
    }

    public function test_first_and_next()
    {
        $this->assertEquals($this->my->First()->id, 'one');
        $this->assertEquals($this->my->Next()->id, 'two');
        $this->assertEquals($this->my->Next()->id, 'three');
        $this->assertEquals($this->my->Next()->id, 'four');
        $this->assertFalse($this->my->Next());
    }

    public function test_Reset()
    {
        $first = $this->my->First();
        $this->my->Reset();
        $this->assertEquals($first, $this->my->Next());
    }

    public function test_Size()
    {
        $this->assertEquals(4, $this->my->Size());
    }

    public function test_Sort()
    {
        $this->my->Sort();
        $this->assertEquals('four', $this->my->First()->id);
        // test non-string items
        $this->my->Sort('val');
    }

    public function test_Next()
    {
        $collection = $this->my->setService(new \stdClass);
        $this->assertFalse($collection->next());
        
        $collection->setService(new Ethel);
        $this->assertNotNull($collection->next());
    }
    
    /**
     * @expectedException OpenCloud\Common\Exceptions\DomainError
     */
    public function test_Select()
    {
        $this->assertEquals(4, $this->my->Size());
        
        $this->my->Select(function($item) {
            return strpos($item->id, 'o') !== false;
        });
        
        $this->assertEquals(3, $this->my->Size());
        
        // this should cause an error
        $this->my->select(function() {
            return 5;
        });
    }

    public function test_NextPage()
    {
        $this->my->setNextPageCallback(array($this, '_callback'), 'http://something');
        
        $this->assertEquals(
			'OpenCloud\Tests\Common\CollectionTest',
			get_class($this->my->NextPage())
        );
        
        $this->assertEmpty($this->my->nextPage());
    }
    
    public function _callback($a, $b)
    {
        
    }

}
