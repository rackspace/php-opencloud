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

namespace OpenCloud\Tests\ObjectStore\Upload;

use Guzzle\Http\Message\Response;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\ObjectStore\Upload\ContainerMigration;
use OpenCloud\Tests\ObjectStore\ObjectStoreTestCase;

class ContainerMigrationTest extends ObjectStoreTestCase
{
    public function test_Factory()
    {
        $container1 = new Container($this->service);
        $container2 = new Container($this->service);

        $migration = ContainerMigration::factory($container1, $container2);

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $migration->getOldContainer());
        $this->assertEquals($migration->getOldContainer(), $container1);

        $this->assertInstanceOf('OpenCloud\ObjectStore\Resource\Container', $migration->getNewContainer());
        $this->assertEquals($migration->getNewContainer(), $container2);
    }

    public function test_Options()
    {
        $migration = ContainerMigration::factory(new Container($this->service), new Container($this->service), array(
            'write.batchLimit' => 50
        ));

        $options = $migration->getOptions();

        $this->assertTrue($options->offsetExists('write.batchLimit'));
        $this->assertEquals($options->get('write.batchLimit'), 50);
    }

    public function test_Transfer()
    {
        // collection
        $response1 = new Response(200, array('Content-Type' => 'application/json'), '[{"name":"test_obj_1","hash":"4281c348eaf83e70ddce0e07221c3d28","bytes":14,"content_type":"application\/octet-stream","last_modified":"2009-02-03T05:26:32.612278"}]');
        $this->addMockSubscriber($response1);

        // individual GETs
        $response2 = new Response(200, array('Content-Type' => 'application/json'), '');
        $this->addMockSubscriber($response2);

        // individual PUTs
        $response3 = new Response(201);
        $this->addMockSubscriber($response3);

        $container = new Container($this->service);
        $container->setName('foo');

        $response = $this->service->migrateContainer($container, $container);

        $this->assertInternalType('array', $response);
        $this->assertInstanceOf('Guzzle\Http\Message\EntityEnclosingRequest', $response[0]);
    }
}
