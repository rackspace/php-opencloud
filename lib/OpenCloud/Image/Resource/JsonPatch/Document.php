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

namespace OpenCloud\Image\Resource\JsonPatch;

/**
 * Class which represents a JSON Patch document, which represents an array of objects. Each object represents a single
 * operation to be applied to the target JSON document. See RFC 6902 for details.
 *
 * @see http://tools.ietf.org/html/rfc6902
 * @package OpenCloud\Images\Resource\JsonPatch
 */
class Document
{
    /** @var array JSON Patch operations */
    protected $operations;

    /**
     * @return array
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * Add a new JSON operation to the document
     *
     * @param Operation $operation
     */
    public function addOperation(Operation $operation)
    {
        $this->operations[] = $operation;
    }

    /**
     * Encode all the operations into a flat structure for HTTP transfer
     *
     * @return string
     */
    public function getResponseBody()
    {
        $this->validateOperations();

        return Encoder::encode($this->operations);
    }

    /**
     * Ensure each operation is valid
     */
    protected function validateOperations()
    {
        foreach ($this->operations as $operation) {
            $operation->validate();
        }
    }

    /**
     * Cast this document as a string
     *
     * @return string
     */
    public function toString()
    {
        return (string) $this->getResponseBody();
    }
}
