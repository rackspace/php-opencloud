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

use OpenCloud\Image\Enum\Document as DocumentEnum;

/**
 * Class that encodes a JSON document object into a flat string format
 *
 * @package OpenCloud\Images\Resource\JsonPatch
 */
class Encoder
{
    /** @var array Required transformations for reserved characters */
    protected static $transformations = array(
        '~' => '~0',
        '/' => '~1'
    );

    /**
     * Encode the
     * @param array $operations
     * @return string
     */
    public static function encode(array $operations)
    {
        $lines = array();

        foreach ($operations as $operation) {
            $lines[] = sprintf(
                '{"%s": "%s", "%s": "%s", "%s": "%s"}',
                DocumentEnum::OP, $operation->getType(),
                DocumentEnum::PATH, $operation->getPath(),
                DocumentEnum::VALUE, self::transform($operation->getValue())
            );
        }

        return sprintf('[%s]', implode($lines, ','));
    }

    /**
     * Search a given string and transform any reserved characters into their safe version
     *
     * @param $value
     * @return string
     */
    public static function transform($value)
    {
        return strtr((string) $value, self::$transformations);
    }
}
