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

namespace OpenCloud\Tests\Orchestration\Resource;

use OpenCloud\Orchestration\Resource\ResourceType;
use OpenCloud\Tests\Orchestration\OrchestrationTestCase;

class ResourceTypeTest extends OrchestrationTestCase
{
    /**
     * @expectedException OpenCloud\Common\Exceptions\CreateError
     */
    public function testCannotCreate()
    {
        $this->resourceType->create();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\UpdateError
     */
    public function testCannotUpdate()
    {
        $this->resourceType->update();
    }

    /**
     * @expectedException OpenCloud\Common\Exceptions\DeleteError
     */
    public function testCannotDelete()
    {
        $this->resourceType->delete();
    }

    public function testGetTemplate()
    {
        $template = '{"HeatTemplateFormatVersion":"2012-12-12","Outputs":{"private_key":{"Description":"The private key if it has been saved.","Value":"{\"Fn::GetAtt\": [\"KeyPair\", \"private_key\"]}"},"public_key":{"Description":"The public key.","Value":"{\"Fn::GetAtt\": [\"KeyPair\", \"public_key\"]}"}},"Parameters":{"name":{"Description":"The name of the key pair.","Type":"String"},"public_key":{"Description":"The optional public key. This allows users to supply the public key from a pre-existing key pair. If not supplied, a new key pair will be generated.","Type":"String"},"save_private_key":{"AllowedValues":["True","true","False","false"],"Default":false,"Description":"True if the system should remember a generated private key; False otherwise.","Type":"String"}},"Resources":{"KeyPair":{"Properties":{"name":{"Ref":"name"},"public_key":{"Ref":"public_key"},"save_private_key":{"Ref":"save_private_key"}},"Type":"OS::Nova::KeyPair"}}}';
        $this->addMockSubscriber($this->makeResponse($template));

        $this->assertEquals($template, $this->resourceType->getTemplate());
    }
}
