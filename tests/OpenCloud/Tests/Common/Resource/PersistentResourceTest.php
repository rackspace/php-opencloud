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

namespace OpenCloud\Tests\Common\Resource;

use OpenCloud\Tests\OpenCloudTestCase;
use OpenCloud\Common\Resource\PersistentResource;
use OpenCloud\Identity\Service as IdentityService;

class PublicPersistentResource extends PersistentResource
{
    protected $aliases = array(
        'foo_bar' => 'fooBar'
    );

    protected $updateKeys = array(
        'baz',
        'tags',
        'domains',
        'origins',
        'status'
    );

    public function recursivelyAliasPropertyValue($propertyValue)
    {
        return parent::recursivelyAliasPropertyValue($propertyValue);
    }

    public function getUpdateablePropertiesAsArray()
    {
        return parent::getUpdateablePropertiesAsArray();
    }

    public function generateJsonPatch($updateParams)
    {
        return parent::generateJsonPatch($updateParams);
    }
}

class PersistentResourceTest extends OpenCloudTestCase
{
    private $persistentResource;

    public function setupObjects()
    {
        $service = IdentityService::factory($this->client);
        $this->persistentResource = new PublicPersistentResource($service);
    }

    public function testRecursivelyAliasPropertyValueWithScalars()
    {
        $this->assertEquals(11,
            $this->persistentResource->recursivelyAliasPropertyValue(11));
        $this->assertEquals("foobar",
            $this->persistentResource->recursivelyAliasPropertyValue("foobar"));
        $this->assertEquals("fooBar",
            $this->persistentResource->recursivelyAliasPropertyValue("fooBar"));
        $this->assertEquals(false,
            $this->persistentResource->recursivelyAliasPropertyValue(false));
    }

    public function testRecursivelyAliasPropertyValueWithIndexedArrays()
    {
        $this->assertEquals(array(18),
            $this->persistentResource->recursivelyAliasPropertyValue(array(18)));
        $this->assertEquals(array("foobar"),
            $this->persistentResource->recursivelyAliasPropertyValue(array("foobar")));
        $this->assertEquals(array("fooBar"),
            $this->persistentResource->recursivelyAliasPropertyValue(array("fooBar")));
    }

    public function testRecursivelyAliasPropertyValueWithAssociativeArrays()
    {
        $this->assertEquals(array("foobar" => "baz"),
            $this->persistentResource->recursivelyAliasPropertyValue(array("foobar" => "baz")));
        $this->assertEquals(array("foo_bar" => "baz"),
            $this->persistentResource->recursivelyAliasPropertyValue(array("fooBar" => "baz")));
        $this->assertEquals(array("qux" => array("foo_bar" => "baz")),
            $this->persistentResource->recursivelyAliasPropertyValue(array("qux" => array("fooBar" => "baz"))));
    }

    public function testRecursivelyAliasPropertyValueWithObjects()
    {
        $obj1 = new \stdClass();
        $obj1->foobar = "baz";

        $obj1Expected = new \stdClass();
        $obj1Expected->foobar = "baz";

        $this->assertEquals($obj1Expected,
            $this->persistentResource->recursivelyAliasPropertyValue($obj1));

        $obj2 = new \stdClass();
        $obj2->fooBar = "baz";

        $obj2Expected = new \stdClass();
        $obj2Expected->foo_bar = "baz";

        $this->assertEquals($obj2Expected,
            $this->persistentResource->recursivelyAliasPropertyValue($obj2));

        $obj3 = new \stdClass();
        $obj3->qux = new \stdClass();
        $obj3->qux->fooBar = "baz";

        $obj3Expected = new \stdClass();
        $obj3Expected->qux = new \stdClass();
        $obj3Expected->qux->foo_bar = "baz";

        $this->assertEquals($obj3Expected,
            $this->persistentResource->recursivelyAliasPropertyValue($obj3));
    }

    public function testGetUpdateablePropertiesAsArray()
    {
        $this->persistentResource->id = 17;
        $this->persistentResource->tags = array('foo', 'bar');
        $this->persistentResource->domains = array(
            (object) array('domain' => 'foo.phpopencloud.com'),
            array('domain' => 'bar.phpopencloud.com')
        );
        $this->persistentResource->origins = array(
            array('origin' => 'origin1.phpopencloud.com')
        );
        $this->persistentResource->status = (object) array('message' => 'Creation in progress');

        $expectedArray = array(
            'tags' => array('foo', 'bar'),
            'domains' => array(
                (object) array('domain' => 'foo.phpopencloud.com'),
                array('domain' => 'bar.phpopencloud.com')
            ),
            'origins' => array(
                array('origin' => 'origin1.phpopencloud.com'),
            ),
            'status' => (object) array('message' => 'Creation in progress')
        );

        $this->assertEquals($expectedArray, $this->persistentResource->getUpdateablePropertiesAsArray());
    }

    public function testGenerateJsonPatch()
    {
        $this->persistentResource->id = 17;
        $this->persistentResource->tags = array('foo', 'bar');
        $this->persistentResource->domains = array(
            array('domain' => 'foo.phpopencloud.com'),
            array('domain' => 'bar.phpopencloud.com')
        );
        $this->persistentResource->origins = array(
            array('origin' => 'origin1.phpopencloud.com')
        );
        $this->persistentResource->status = array('message' => 'Creation in progress');
        $this->persistentResource->baz = (object) array( 'fooBar' => 'barbar');

        $updateParams = array(
            'tags'    => array('foo', 'qux', 'baz'),
            'domains' => array(
                array('domain' => 'foo.phpopencloud.com')
            ),
            'origins' => array(
                array('origin' => 'origin1.phpopencloud.com'),
                array('origin' => 'origin2.phpopencloud.com')
            ),
            'baz' => array('fooBar' => 'barbarbar')
        );

        $expectedJsonPatch = json_encode(array(
            array('op' => 'replace', 'path' => '/baz/foo_bar', 'value' => 'barbarbar'),
            array('op' => 'add', 'path' => '/tags/2', 'value' => 'baz'),
            array('op' => 'replace', 'path' => '/tags/1', 'value' => 'qux'),
            array('op' => 'remove', 'path' => '/domains/1'),
            array('op' => 'add', 'path' => '/origins/1', 'value' => array("origin" => "origin2.phpopencloud.com"))
        ));

        $actualJsonPatch = $this->persistentResource->generateJsonPatch($updateParams);
        $this->assertEquals($expectedJsonPatch, $actualJsonPatch);
    }
}
