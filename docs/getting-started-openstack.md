# Installing the SDK

You must install through Composer, because this library has a few dependencies:

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Require php-opencloud as a dependency
php composer.phar require rackspace/php-opencloud:dev-master
```

Once you have installed the library, you will need to load Composer's autoloader (which registers all the required
namespaces):

```php
require 'vendor/autoload.php';
```

And you're good to go!

# Quick deep-dive: building some Nova instances

In this example, you will write code that will create a Nova instance running Ubuntu.

### 1. Setup the client and pass in your credentials

To authenticate against Keystone:

```php
use OpenCloud\OpenStack;

$client = new OpenStack('http://my-openstack.com:35357/v2.0/', array(
    'username'   => 'foo',
    'password'   => 'bar',
    'tenantName' => 'baz'
));
```

You will need to substitute in the public URL endpoint for your Keystone service, as well as your `username`, `password`
and `tenantName`. You can also specify your `tenantId` instead of `tenantName` if you prefer.

### 2. Pick what service you want to use

In this case, we want to use the Nova service:

```php
$compute = $client->computeService('nova', 'regionOne');
```

The first argument is the __name__ of the service as it appears in the OpenStack service catalog. If in doubt, you can
leave blank and it will revert to the default name for the service. The second argument is the region. The third and
last argument is the type of URL; you may use either `publicURL` or `internalURL`.

### 3. Select your server image

Instances are based on "images", which are effectively just the type of operating system you want. Let's go through the
list and find an Ubuntu one:

```php
$images = $compute->imageList();
foreach ($images as $image) {
    if (strpos($image->name, 'Ubuntu') !== false) {
        $ubuntu = $image;
        break;
    }
}
```

Alternatively, if you already know the image ID, you can do this much easier:

```php
$ubuntu = $compute->image('868a0966-0553-42fe-b8b3-5cadc0e0b3c5');
```

## 4. Select your flavor

There are different server specs - some which offer 1GB RAM, others which offer a much higher spec. The 'flavor' of an
instance is its hardware configuration. So if you want a 2GB instance but don't know the ID, you have to traverse the list:

```php
$flavors = $compute->flavorList();
foreach ($flavors as $flavor) {
    if (strpos($flavor->name, '2GB') !== false) {
        $twoGbFlavor = $flavor;
        break;
    }
}
```

Again, it's much easier if you know the ID:

```php
$twoGbFlavor = $compute->flavor('4');
```

## 5. Thunderbirds are go!

Okay, you're ready to spin up a server:

```php
$server = $compute->server();

try {
    $response = $server->create(array(
        'name'     => 'My lovely server',
        'image'    => $ubuntu,
        'flavor'   => $twoGbFlavor
    ));
} catch (\Guzzle\Http\Exception\BadResponseException $e) {

    // No! Something failed. Let's find out:

    $responseBody = (string) $e->getResponse()->getBody();
    $statusCode   = $e->getResponse()->getStatusCode();
    $headers      = $e->getResponse()->getHeaderLines();

    echo sprintf("Status: %s\nBody: %s\nHeaders: %s", $statusCode, $responseBody, implode(', ', $headers));
}
```

As you can see, you're creating a server called "My lovely server" - this will take a few minutes for the build to
complete. You can always check the progress by logging into your Controller node and running:

`nova list`

You can also execute a polling function immediately after the `create` method that checks the build process:

```php
use OpenCloud\Compute\Constants\ServerState;

$callback = function($server) {
    if (!empty($server->error)) {
        var_dump($server->error);
        exit;
    } else {
        echo sprintf(
            "Waiting on %s/%-12s %4s%%",
            $server->name(),
            $server->status(),
            isset($server->progress) ? $server->progress : 0
        );
    }
};

$server->waitFor(ServerState::ACTIVE, 600, $callback);
```
So, the server will be polled until it is in an `ACTIVE` state, with a timeout of 600 seconds. When the poll happens, the
callback function is executed - which in this case just logs some output.

# More fun with Nova

Once you've booted up your instance, you can use other API operations to monitor your Compute nodes. To list every
node on record, you can execute:

```php
$servers = $compute->serverList();

foreach ($servers as $server) {
    // do something with each server...
    echo $server->name, PHP_EOL;
}
```

or, if you know a particular instance ID you can retrieve its details:

```php
$server = $compute->server('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxx');
```

allowing you to update its properties:

```php
$server->update(array(
   'name' => 'New server name'
));
```

or delete it entirely:

```php
$server->delete();
```

# Next steps

Consult our [documentation](https://github.com/rackspace/php-opencloud/tree/master/docs/userguide) about other services
you can use, like [Keystone](https://github.com/rackspace/php-opencloud/tree/master/docs/userguide/Identity) or
[Swift](https://github.com/rackspace/php-opencloud/tree/master/docs/userguide/ObjectStore). If you have any questions or
troubles, feel free to e-mail sdk-support@rackspace.com or open a Github issue with details.