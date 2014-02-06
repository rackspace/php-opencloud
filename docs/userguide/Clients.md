# Clients

## Overview

A Client is the object responsibile for issuing HTTP requests and receiving responses from the API. In short, it forms the core of the SDK because it controls how functionality is executed. All services depend on the client to work.

Users have access to two types of client: `OpenCloud\OpenStack` and `OpenCloud\Rackspace`. The latter extends the former, meaning that much of the functionality is shared between them. The OpenStack client extends functionality from other base classes, that trace all the way back to Guzzle's root class:

1. `Guzzle\Http\Client`
2. `OpenCloud\Common\Http\Client`
3. `OpenCloud\OpenStack`
4. `OpenCloud\Rackspace`

## Initializing a client

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

## Authentication

The Client does not automatically authenticate against the API on object creation - it waits for an API call. When this happens, it checks whether the current "token" has expired, and (re-)authenticates if necessary.

You can force authentication, by calling:

```php 
$client->authenticate();
```

If the credentials are incorrect, a `401` error will be returned. If credentials are correct, a `200` status is returned with your Service Catalog.

## Service Catalog

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

## Default HTTP headers

To set default HTTP headers:

```php
$client->setDefaultOption('headers/X-Custom-Header', 'FooBar');
```

## Other functionality

For a full list of functionality provided by Guzzle, please consult the [official documentation](http://docs.guzzlephp.org/en/latest/http-client/client.html).