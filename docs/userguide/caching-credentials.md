# Caching credentials

You can speed up your API operations by caching your credentials in a (semi-)permanent location, such as your
DB or local filesystem. This enable subsequent requests to access a shared resource, instead of repetitively having to
re-authenticate on every thread of execution.

Tokens are valid for 24 hours, so you can effectively re-use the same cached value for that period. If you try to use
a cached version that has expired, an authentication request will be made.

## Filesystem example

In this example, credentials will be saved to a file in the local filesystem. Be sure to exclude it from your VCS.

```php
use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => 'foo',
    'apiKey'   => 'bar'
));

$cacheFile = __DIR__ . '/.opencloud_token';

// If the cache file exists, try importing it into the client
if (file_exists($cacheFile)) {
    $data = unserialize(file_get_contents($cacheFile));
    $client->importCredentials($data);
}

$token = $client->getTokenObject();

// If no token exists, or the current token is expired, re-authenticate and save the new token to disk
if (!$token || ($token && $token->hasExpired())) {
    $client->authenticate();
    file_put_contents($cacheFile, serialize($client->exportCredentials()));
}
```

In tests, the above code shaved about 1-2s off the execution time.