<?php

namespace OpenCloud\Images\Resource\JsonPatch;

use OpenCloud\Images\Enum\Document as DocumentEnum;

class Document 
{
    protected $operations;

    public function getOperations()
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }

    public function getResponseBody()
    {
        $this->validateOperations();

        return Encoder::encode($this->operations);
    }

    protected function validateOperations()
    {
        foreach ($this->operations as $operation) {
            $operation->validate();
        }
    }

    public function toString()
    {
        return (string) $this->getResponseBody();
    }
} 