<?php

namespace OpenCloud\Tests\Images\Resource\JsonPatch;

use OpenCloud\Images\Enum\OperationType;
use OpenCloud\Images\Resource\JsonPatch\Document;
use OpenCloud\Images\Resource\JsonPatch\Operation;
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