<?php
/**
 * PHP OpenCloud library.
 * 
 * @copyright 2014 Rackspace Hosting, Inc. See LICENSE for information.
 * @license   https://www.apache.org/licenses/LICENSE-2.0
 * @author    Jamie Hannaford <jamie.hannaford@rackspace.com>
 */

namespace OpenCloud\Tests\Common\Collection;

use OpenCloud\Tests\OpenCloudTestCase;

class ArrayCollectionTest extends OpenCloudTestCase
{

    public function test_Basic_Operations()
    {
        $iterator = $this->getMockForAbstractClass('OpenCloud\Common\Collection\ArrayCollection');

        $iterator->setElements(array('foo', 'bar', 'baz', 'one', 'two'));

        $iterator->offsetSet(5, 'three');

        $this->assertEquals('three', $iterator->offsetGet(5));
        $this->assertEquals(6, $iterator->count());

        $iterator->append('four');
        $this->assertEquals(7, $iterator->count());

        $this->assertTrue(isset($iterator[6]));
        $this->assertFalse($iterator->offsetExists(600));

        $this->assertTrue($iterator->valueExists('one'));
        $this->assertFalse($iterator->valueExists('fifty'));

        unset($iterator[0]);
        $this->assertFalse($iterator->valueExists('foo'));
    }

}