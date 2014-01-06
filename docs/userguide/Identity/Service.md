# Identity service

## Intro

The Identity service is regionless, so you do not need to specify a region when instantiating the service object. Although this was primarily based on Rackspace's implementation of Cloud Identity, it should also work for OpenStack Keystone.

## A note on object creation

Normally, when services are created the client handles authenticates automatically. But because Keystone/Identity is fundamental to the authentication process itself, it proves difficult to do this procedure as its normally done. For this reason, you have two options when creating the service object:

1: Use the client's factory method

```php
$identity = $client->identityService();
```

2: Authenticate manually

```php
use OpenCloud\Identity\Service as IdentityService;

$identity = IdentityService::factory($client);
$identity->getClient()->authenticate();
```