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