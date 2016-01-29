<?php

namespace Rackspace\Integration;

class Utils extends \OpenStack\Integration\Utils
{
    public static function getAuthOpts(array $options = [])
    {
        return array_merge($options, [
            'username' => getenv('RS_USERNAME'),
            'apiKey'   => getenv('RS_API_KEY'),
            'region'   => getenv('RS_REGION'),
        ]);
    }
}