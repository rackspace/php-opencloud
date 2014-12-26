# Complete User Guide for the CDN Service

CDN is a service that you can use to manage your CDN-enabled domains and the origins and assets associated with those domains.

## Table of contents
  * [Concepts](#concepts)
  * [Prerequisites](#prerequisites)
    * [Client](#client)
    * [CDN service](#cdn-service)
  * [Services](#services)
    * [Create a service](#create-a-service-to-represent-your-web-application)
    * [List Services](#list-Services)
    * [Get a service](#get-a-service)
    * [Purge cached service assets](#purge-cached-service-assets)
    * [Update a service](#update-a-service)
    * [Delete a service](#delete-a-service)
  * [Flavors](#flavors)
    * [Create a flavor](#create-a-flavor)
    * [List flavors](#list-flavors)
    * [Get a flavor](#get-a-flavor)
    * [Delete a flavor](#delete-a-flavor)

## Concepts

To use the CDN service effectively, you should understand the following
key concepts:

* **Content delivery network**: A content delivery network (CDN) is a system of multiple computers that contains copies of data stored at various network nodes. A CDN is designed to improve performance of publicly distributed assets. Assets can be anything from website content, to web application components, to media such as videos, ads, and interactive experiences.  CDNs decrease the load time of these assets by caching them on edge servers, also called points of presence (PoPs).  Edge servers are distributed around the globe, meaning requests only travel to a local location to grab assets, rather than to and from a data center based far from the end user.

* **Edge node**: CDN providers have many points of presence (PoP) servers around the world. These servers are known as edge nodes. These edge nodes cache the content and serve it directly to customers, thus reducing transit time to a customers location.

* **Edge server**: An edge server is the same as an edge node.

* **Origin**: An origin is an address (IP or domain) from which the CDN provider pulls content. A service can have multiple origins.

* **Flavor**: A flavor is a configuration option. A flavor enables you to choose from a generic setting that is powered by one or more CDN providers.

* **Service**: A service represents your web application that has its content cached to the edge nodes.

* **Status**: The status indicates the current state of the service. The time it takes for a service configuration to be distributed amongst a CDN provider cache can vary.

* **Purge**: Purging removes content from the edge servers - thus invalidating the content - so that it can be refreshed from your origin servers.
        
* **Caching rule**: A caching rule provides you with fine-grained control over the time-to-live (TTL) of an object. When the TTL expires for an object, the edge node pulls the object from the origin again.

* **Restriction**: A restriction enables you to define rules about who can or cannot access content from the cache. Examples of a restriction are allowing requests only from certain domains, geographies, or IP addresses.


## Prerequisites

### Client
To use the CDN service, you must first instantiate a `OpenStack` or `Rackspace` client object.

* If you are working with an OpenStack cloud, instantiate an `OpenCloud\OpenStack` client as follows:

    ```php
    use OpenCloud\OpenStack;

    $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
        'username' => '<YOUR OPENSTACK CLOUD ACCOUNT USERNAME>',
        'password' => '<YOUR OPENSTACK CLOUD ACCOUNT PASSWORD>'
    ));
    ```

* If you are working with the Rackspace cloud, instantiate a `OpenCloud\Rackspace` client as follows:

    ```php
    use OpenCloud\Rackspace;

    $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
     ));
    ```

### CDN service

All CDN operations are done via an _CDN service object_. To
instantiate this object, call the `cdnService` method on the `$client`
object. This method takes one argument:

| Position | Description | Data type | Required? | Default value | Example value |
| -------- | ----------- | ----------| --------- | ------------- | ------------- |
|  1       | Name of the service, as it appears in the service catalog | String | No | `null`; automatically determined when possible | `rackCDN` |


```php
$cdnService = $client->cdnService();
```

## Services

A service represents your web application that has its content cached to the edge nodes.

### Create a service (to represent your web application)


This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | A human-readable name for the service. This name must be unique. | String | No | `null` | `acme_site` |
| `domains` | An array of associative arrays, each specifying a domain name for your service. | Array of associative arrays | Yes | `null` | `array( array( 'domain' => 'www.acme.com' ), array ( 'domain' => 'acme.com' ) )` |
| `origins` | An array of associative arrays, each specifying an origin server for your service. The `port`, `ssl`, and `rules` elements for each origin server are optional. | Array of associative arrays | Yes | `null` | `array( array( 'origin' => 'origin.acme.com', 'port' => 80, 'ssl' => false, 'rules' => array() ) )` |
| `flavorId` | The flavor used to configure this service. Use the [list flavors](#list-flavors) operation to retrieve a list of all available flavors. | String | Yes| `null` | `cdn` |
| `restrictions` | An array of associative arrays, each specifying a restriction for who can or cannot access content from the CDN cache. | Array of associative arrays | No | `null` | `array( array( 'name' => 'website only', 'rules' => array( array( 'name' => 'mywebsite.com', 'httpHost' => 'www.mywebsite.com' ) ) ) )` |
| `caching` | An array of associative arrays, each specifying a caching rule for your service's assets. | No | `null` | `array( array( 'name' => 'default', 'ttl' => 3600 ) )` |

You can create a service as shown in the following example:

```php
$service = $cdnService->createService(array(
    'name'     => 'acme_site',
    'domains'  => array(
        array(
            'domain' => 'www.acme.com'
        ),
        array(
            'domain' => 'acme.com'
        )
    ),
    'origins'  => array(
        array(
            'origin' => 'origin.acme.com'
        )
    ),
    'flavorId' => 'cdn'
));```

[ [Get the executable PHP script for this example](/samples/CDN/create-service.php) ]

### List Services

You can list all the services you have created as shown in the following example:

```php
$services = $cdnService->listServices();
foreach ($services as $service) {
    /** @var $service OpenCloud\CDN\Resource\Service **/
}
```

[ [Get the executable PHP script for this example](/samples/CDN/list-services.php) ]

### Get a service

You can retrieve a specific service by using that service's name, as shown in the following example:

```php
$service = $cdnService->getservice('acme_site');
/** @var $service OpenCloud\CDN\Resource\Service **/
```

[ [Get the executable PHP script for this example](/samples/CDN/get-service.php) ]

### Update a service

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `domains` | An array of associative arrays, each specifying a domain name for your service. | Array of associative arrays | Yes | `null` | `array( array( 'domain' => 'www.acme.com' ), array ( 'domain' => 'acme.com' ) )` |
| `origins` | An array of associative arrays, each specifying an origin server for your service. The `port`, `ssl`, and `rules` elements for each origin server are optional. | Array of associative arrays | Yes | `null` | `array( array( 'origin' => 'origin.acme.com', 'port' => 80, 'ssl' => false, 'rules' => array() ) )` |
| `flavorId` | The flavor used to configure this service. Use the [list flavors](#list-flavors) operation to retrieve a list of all available flavors. | String | Yes| `null` | `cdn` |
| `restrictions` | An array of associative arrays, each specifying a restriction for who can or cannot access content from the CDN cache. | Array of associative arrays | No | `null` | `array( array( 'name' => 'website only', 'rules' => array( array( 'name' => 'mywebsite.com', 'httpHost' => 'www.mywebsite.com' ) ) ) )` |
| `caching` | An array of associative arrays, each specifying a caching rule for your service's assets. | No | `null` | `array( array( 'name' => 'default', 'ttl' => 3600 ) )` |

You can update a service as shown in the following example:

```php
$service->update(array(
    'origins' => array(
        array(
            'origin' => '44.33.22.11',
            'port'   => 80,
            'ssl'    => false
        )
    )
));
```

[ [Get the executable PHP script for this example](/samples/CDN/update-service.php) ]

### Delete a service

You can delete a service as shown in the following example:

```php
$service->delete();
```

[ [Get the executable PHP script for this example](/samples/CDN/delete-service.php) ]

## Flavors

A flavor is a configuration option. A flavor enables you to choose from a generic setting that is powered by one or more CDN providers.

### Create a flavor

<strong><em>Note: When working with the Rackspace Cloud, this operation requires the `cdn:operator` role.</em></strong>

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `id` | ID of flavor. This ID must be unique. | String | Yes | `null` | `cdn` |
| `providers` | An array of associative arrays, each representing a CDN provider. | Array of associative arrays | Yes | `null` | `array( array( 'provider' => 'akamai', 'links' => array( array( 'rel' => 'provider_url', 'href' => 'http://www.akamai.com' ) ) ) )` |

You can create a flavor as shown in the following example:

```php
$Flavor = $CDNService->createFlavor(array(
    'id' => 'cdn',
    'providers' => array(
        array(
            'name'  => 'akamai',
            'links' => array(
                 'rel'  => 'provider_url',
                 'href' => 'http://www.akamai.com'
            )
        )
    )
));
/** @var $Flavor OpenCloud\CDN\Resource\Flavor **/
```

[ [Get the executable PHP script for this example](/samples/CDN/create-flavor.php) ]

### List flavors

You can list all available flavors as shown in the following example:

```php
$flavors = $cdnService->listFlavors();
foreach ($flavors as $flavor) {
    /** @var $flavor OpenCloud\CDN\Resource\Flavor **/
}
```

[ [Get the executable PHP script for this example](/samples/CDN/list-flavors.php) ]

### Get a flavor

You can retrieve a specific flavor by using that flavor's ID, as shown in the
following example:

```php
$flavor = $cdnService->getFlavor('cdn');
/** @var $flavor OpenCloud\CDN\Resource\Flavor **/
```

[ [Get the executable PHP script for this example](/samples/CDN/get-flavor.php) ]

### Delete a flavor

<strong><em>Note: When working with the Rackspace Cloud, this operation requires the `cdn:operator` role.</em></strong>

You can delete a flavor as shown in the following example:

```php
$flavor->delete();
```

[ [Get the executable PHP script for this example](/samples/CDN/delete-flavor.php) ]
