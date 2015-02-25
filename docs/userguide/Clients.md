# Clients

## Overview

A Client is the object responsible for issuing HTTP requests and receiving responses from the API. In short, it forms the core of the SDK because it controls how functionality is executed. All services depend on the client to work.

Users have access to two types of client: `OpenCloud\OpenStack` and `OpenCloud\Rackspace`. The latter extends the former, meaning that much of the functionality is shared between them. The OpenStack client extends functionality from other base classes, that trace all the way back to Guzzle's root class:

1. `Guzzle\Http\Client`
2. `OpenCloud\Common\Http\Client`
3. `OpenCloud\OpenStack`
4. `OpenCloud\Rackspace`

## 1. Initializing a client

### Rackspace

First, you need to select the Identity endpoint you want to authenticate against. If you're using Rackspace, you can either use the UK or US endpoints. There are class constants defined for your convenience:

- `OpenCloud\Rackspace::US_IDENTITY_ENDPOINT` (https://identity.api.rackspacecloud.com/v2.0)
- `OpenCloud\Rackspace::UK_IDENTITY_ENDPOINT` (https://lon.identity.api.rackspacecloud.com/v2.0)

Then you need to find your username and apiKey. Your username will be visible at the top right of the Rackspace Control panel; and your API key can be retrieved by going to Account Settings. Once this is done:

```php
use OpenCloud\OpenStack;

$client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => 'foo',
    'apiKey'   => 'bar'
));
```

### OpenStack

To initialize an OpenStack client, the process is the same:

```php
use OpenCloud\OpenStack;

$client = new OpenStack('http://identity.my-openstack.com/v2.0', array(
	'username' => 'foo',
    'password' => 'bar'
));
```

#### 1.2 Logger injection
As the `Rackspace` client extends the `OpenStack` client, they both support passing `$options` as an array via the constructor's third parameter. The options are passed as a config to the `Guzzle` client, but also allow to inject your own `Logger`. 

Your logger should implement the `Psr\Log\LoggerInterface` [as defined in PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md). Example of a compatible logger is [`Monolog`](https://github.com/Seldaek/monolog). When the client does create a service, it will inject the logger if one is available.

To inject a `LoggerInterface` compatible logger into a new `Client`:

```php
use Monolog\Logger;
use OpenCloud\OpenStack;

// create a log channel
$log = new Logger('name');

$client = new OpenStack('http://identity.my-openstack.com/v2.0', array(
	'username' => 'foo',
	'password' => 'bar'
), array(
	'logger' => $log,
));
```

## 2. Authentication

The Client does not automatically authenticate against the API on object creation - it waits for an API call. When this happens, it checks whether the current "token" has expired, and (re-)authenticates if necessary.

You can force authentication, by calling:

```php 
$client->authenticate();
```

If the credentials are incorrect, a `401` error will be returned. If credentials are correct, a `200` status is returned with your Service Catalog.

## 3. Service Catalog

The Service Catalog is returned on successful authentication, and is composed of all the different API services available to the current tenant. All of this functionality is encapsulated in the `Catalog` object, which allows you greater control and interactivity.

```php
/** @var OpenCloud\Common\Service\Catalog */
$catalog = $client->getCatalog();

// Return a list of OpenCloud\Common\Service\CatalogItem objects
foreach ($catalog->getItems() as $catalogItem) {
	
    $name = $catalogItem->getName();
    $type = $catalogItem->getType();
    
    if ($name == 'cloudServersOpenStack' && $type == 'compute') {
    	break;
    }
    
    // Array of OpenCloud\Common\Service\Endpoint objects
    $endpoints = $catalogItem->getEndpoints();
    foreach ($endpoints as $endpoint) {
    	if ($endpoint->getRegion() == 'DFW') {
        	echo $endpoint->getPublicUrl();
        }
    }
}
```

As you can see, you have access to each Service's name, type and list of endpoints. Each endpoint provides access to the specific region, along with its public and private endpoint URLs.

## 4. Default HTTP headers

To set default HTTP headers:

```php
$client->setDefaultOption('headers/X-Custom-Header', 'FooBar');
```

## User agents

php-opencloud will send a default `User-Agent` header for every HTTP request, unless a custom value is provided by the end-user. The default header will be in this format:

> User-Agent: OpenCloud/xxx cURL/yyy PHP/zzz

where `xxx` is the current version of the SDK, `yyy` is the current version of cURL, and `zzz` is the current PHP version. To override this default, you must run:

```php
$client->setUserAgent('MyCustomUserAgent');
```

which will result in:

> User-Agent: MyCustomUserAgent

If you want to set a _prefix_ for the user agent, but retain the default `User-Agent` as a suffix, you must run:

```php
$client->setUserAgent('MyPrefix', true);
```

which will result in:

> User-Agent: MyPrefix OpenCloud/xxx cURL/yyy PHP/zzz

where `$client` is an instance of `OpenCloud\OpenStack` or `OpenCloud\Rackspace`.

## 5. Other functionality

For a full list of functionality provided by Guzzle, please consult the [official documentation](http://docs.guzzlephp.org/en/latest/http-client/client.html).
