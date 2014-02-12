<?php

namespace OpenCloud\Tests\Images\Resource\JsonPatch;

use OpenCloud\Images\Enum\OperationType;
use OpenCloud\Images\Resource\JsonPatch\Document;
use OpenCloud\Images\Resource\JsonPatch\Encoder;
use OpenCloud\Images\Resource\JsonPatch\Operation;
use OpenCloud\Images\Resource\Schema\Property;
use OpenCloud\Tests\OpenCloudTestCase;

class EncoderTest extends OpenCloudTestCase
{
    public function test_Escaping_Slash()
    {
        $operation = new Operation();
        $operation->setPath('/foo');
        $operation->setType(OperationType::REPLACE);
        $operation->setValue('/bar/baz');

        $encoded = Encoder::encode(array($operation));

        $this->assertEquals('[{"op": "replace", "path": "/foo", "value": "~1bar~1baz"}]', $encoded);
    }

    public function test_Escaping_Tilde()
    {
        $property = new Property();
        $property->setName('~foo');

        $operation = new Operation();
        $operation->setPath($property->getPath());
        $operation->setType(OperationType::REPLACE);
        $operation->setValue('bar~baz~');

        $encoded = Encoder::encode(array($operation));

        $this->assertEquals('[{"op": "replace", "path": "/~0foo", "value": "bar~0baz~0"}]', $encoded);
    }

    public function test_Transform()
    {
        $this->assertEquals('~0', Encoder::transform('~'));
        $this->assertEquals('~1', Encoder::transform('/'));

        $this->assertEquals('A', Encoder::transform('A'));
        $this->assertEquals('!!!!', Encoder::transform('!!!!'));
    }
} 