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

# Quick deep-dive: building some cloud servers

In this example, you will write code that will create a Cloud Server running Ubuntu.

### 1. Setup the client and pass in your credentials

To authenticate against the Rackspace API and use its services:

```php
<?php

require 'vendor/autoload.php';

use OpenCloud\Rackspace;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => 'foo',
    'apiKey'   => 'bar'
));
```

Alternatively, if you would like to validate against your own API, or just want to access OpenStack services:

```php
use OpenCloud\OpenStack;

$client = new OpenStack('http://identity.my-openstack.com/v2.0/', array(
    'username' => 'foo',
    'password' => 'bar'
));
```

You can see in the first example that the constant `Rackspace::US_IDENTITY_ENDPOINT` is just a string representation of
Rackspace's identity endpoint (`https://identity.api.rackspacecloud.com/v2.0/`). Another difference is that Rackspace
uses API key for authentication, whereas OpenStack uses a generic password.

### 2. Pick what service you want to use

In this case, we want to use the Compute (Nova) service:

```php
$compute = $client->computeService('cloudServersOpenStack', 'ORD');
```

The first argument is the __name__ of the service as it appears in the OpenStack service catalog. If in doubt, you can
leave blank and it will revert to the default name for the service. The second argument is the region; you may use:

- __DFW__ (Dallas)
- __ORD__ (Chicago)
- __IAD__ (Virginia)
- __LON__ (London)
- __HKG__ (Hong Kong)
- __SYD__ (Sydney)

The third and last argument is the type of URL; you may use either `publicURL` or `internalURL`. If you select `internalUrl`
all API traffic will use ServiceNet (internal IPs) and will receive a performance boost.

### 3. Select your server image

Servers are based on "images", which are effectively just the type of operating system you want. Let's go through the list
and find an Ubuntu one:

```php
$images = $compute->imageList();
while ($image = $images->next()) {
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

There are different server specs - some which offer 1GB RAM, others which offer a much higher spec. The 'flavor' of a
server is its hardware configuration. So if you want a 2GB instance but don't know the ID, you have to traverse the list:

```php
$flavors = $compute->flavorList();
while ($flavor = $flavors->next()) {
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
use OpenCloud\Compute\Constants\Network;

$server = $compute->server();

try {
    $response = $server->create(array(
        'name'     => 'My lovely server',
        'image'    => $ubuntu,
        'flavor'   => $twoGbFlavor,
        'networks' => array(
            $compute->network(Network::RAX_PUBLIC),
            $compute->network(Network::RAX_PRIVATE)
        )
    ));
} catch (\Guzzle\Http\Exception\BadResponseException $e) {

    // No! Something failed. Let's find out:

    $responseBody = (string) $e->getResponse()->getBody();
    $statusCode   = $e->getResponse()->getStatusCode();
    $headers      = $e->getResponse()->getHeaderLines();

    echo sprintf("Status: %s\nBody: %s\nHeaders: %s", $statusCode, $responseBody, implode(', ', $headers));
}
```

As you can see, you're creating a server called "My lovely server", and you've inserted it in two networks: the Rackspace
private network (ServiceNet), and the Rackspace public network (for Internet connectivity). This will take a few
minutes for the build to complete.

You can also call a polling function that checks on the build process:

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