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

namespace OpenCloud\Tests\Image\Resource\JsonPatch;

use OpenCloud\Image\Enum\OperationType;
use OpenCloud\Image\Resource\JsonPatch\Document;
use OpenCloud\Image\Resource\JsonPatch\Operation;
use OpenCloud\Tests\OpenCloudTestCase;

class DocumentTest extends OpenCloudTestCase
{
    public function test_Adding_Operations()
    {
        $document = new Document();

        $limit = 5;

        for ($i = 0; $i < $limit; $i++) {
            $document->addOperation(new Operation());
        }

        $this->assertCount($limit, $document->getOperations());
    }

    public function test_To_String()
    {
        $document = new Document();

        $operation = new Operation();
        $operation->setPath('/foo');
        $operation->setType(OperationType::REPLACE);
        $operation->setValue('bar');

        $document->addOperation($operation);

        $this->assertEquals('[{"op": "replace", "path": "/foo", "value": "bar"}]', $document->toString());
    }
}
