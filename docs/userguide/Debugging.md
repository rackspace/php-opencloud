# Debugging

There are two important debugging strategies to use when encountering problems
with HTTP transactions.

## Strategy 1: Meaningful exception handling

If the API returns a `4xx` or `5xx` status code, it indicates that there was an
error with the sent request, meaning that the transaction cannot be adequately
completed.

The Guzzle HTTP component, which forms the basis of our SDK's transport layer,
utilizes [numerous exception classes](https://github.com/guzzle/guzzle/tree/master/src/Guzzle/Http/Exception)
to handle this error logic.

The two most common exception classes are:

* `Guzzle\Http\Exception\ClientErrorResponseException`, which is thrown when a
`4xx` response occurs

* `Guzzle\Http\Exception\ServerErrorResponseException`, which is thrown when a
`5xx` response occurs

Both of these classes extend the base `BadResponseException` class.

This provides you with the granularity you need to debug and handle exceptions.

### An example with Swift

If you're trying to retrieve a Swift resource, such as a Data Object, and you're
not completely certain that it exists, it makes sense to wrap your call in a
try/catch block:

```php
use Guzzle\Http\Exception\ClientErrorResponseException;

try {
    return $service->getObject('foo.jpg');
} catch (ClientErrorResponseException $e) {
    // Okay, the resource probably does not exist
    return false;
} catch (\Exception $e) {
    // Some other exception was thrown, probably critical
    $this->logException($e);
    $this->alertDevs();
}
```

Both `ClientErrorResponseException` and `ServerErrorResponseException` have
two methods that allow you to access the HTTP transaction:

```php
// Find out the faulty request
$request = $e->getRequest();

// Display everything by casting as string
echo (string) $request;

// Find out the HTTP response
$response = $e->getResponse();

// Output that too
echo (string) $response;
```

## Strategy 2: Wire logging

Guzzle provides a [Log plugin](http://docs.guzzlephp.org/en/latest/plugins/log-plugin.html)
that allows you to log everything over the wire, which is useful if you don't
know what's going on.

Here's how you enable it:

#### Install the plugin

```bash
php composer.phar require guzzle/plugin-log:~3.8
```

#### Add to your client

```php
use Guzzle\Plugin\Log\LogPlugin;

$client->addSubscriber(LogPlugin::getDebugPlugin());
```

The above will add a generic logging subscriber to your client, which will be
notified every time a relevant HTTP event is fired off.
