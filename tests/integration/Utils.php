<?php declare(strict_types=1);

namespace Rackspace\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Rackspace\Rackspace;

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

    public static function getHttpClient()
    {
        return new Client([
            'base_uri' => \OpenStack\Common\Transport\Utils::normalizeUrl(Rackspace::US_AUTH),
            'handler'  => HandlerStack::create(),
        ]);
    }
}