<?php

namespace OpenCloud\Images\Resource\JsonPatch;

use OpenCloud\Images\Enum\Document as DocumentEnum;

class Encoder
{
    protected static $transformations = array(
        '~' => '~0',
        '/' => '~1'
    );

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

    public static function transform($value)
    {
        return strtr((string) $value, self::$transformations);
    }
} 