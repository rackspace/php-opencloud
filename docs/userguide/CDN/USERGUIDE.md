# Complete User Guide for the CDN Service

CDN is a service that you can use to manage your CDN-enabled domains and the origins and assets associated with those domains.

## Table of contents
  * [Concepts](#concepts)
  * [Prerequisites](#prerequisites)
    * [Client](#client)
    * [CDN service](#cdn-service)
  * [Services](#services)
    * [Create a service](#create-a-service-to-represent-your-web-application)
    * [List Services](#list-services)
    * [Get a service](#get-a-service)
    * [Update a service](#update-a-service)
    * [Delete a service](#delete-a-service)
  * [Service Assets](#service-assets)
    * [Purge all cached service assets](#purge-all-cached-service-assets)
    * [Purge a specific cached service asset](#purge-a-specific-cached-service-asset)
  * [Flavors](#flavors)
    * [List flavors](#list-flavors)
    * [Get a flavor](#get-a-flavor)

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
| `name` | A human-readable name for the service. This name must be unique. | String | Yes | - | `acme_site` |
| `flavorId` | The ID of the flavor to use for this service. | String | Yes | - | `cdn` |
| `domains` | List of domain for your service. | Array of associative arrays | Yes | - | `array( ... )` |
| `domains[n]` | Information about a domain for your service. | Associative array | Yes | - | `array( ... )` |
| `domains[n]['domain']` | A domain name used by your service. | String | Yes | - | 'www.acme.com' |
| `domains[n]['protocol']` | The protocol used by your service web site, `http` or `https`. | String | No | `http` | `http` |
| `origins` | List of origin servers for your service.  | Array of associative arrays | Yes | - | `array( ... )` |
| `origins[n]` | Information about an origin server for your service. | Associative array | Yes | - | `array( ... )` |
| `origins[n]['origin']` | The origin server address, from where the CDN will pull your web site's assets. | String | Yes | - | `origin.acme.com` |
| `origins[n]['port']` | The origin server's port. | Integer | No | 80 | `8080` |
| `origins[n]['ssl']` | Whether origin server uses SSL. | Boolean | No | `false` | `true` |
| `origins[n]['rules']` | List of rules defining the conditions when this origin should be accessed. | Array of associative arrays | No | `null` | `array( ... )` |
| `origins[n]['rules'][n]` | Information about an access rule. | Associative array | No | `null` | `array( ... )` |
| `origins[n]['rules'][n]['name']` | A human-readable name of the rule. | String | No | `null` | `images` |
| `origins[n]['rules'][n]['requestUrl']` | The request URL this rule should match (regex supported). | String | No | `null` | `^/images/.+$` |
| `caching` | List of TTL rules for assets of this service. | Array of associative arrays | No | `null` | `array( ... )` |
| `caching[n]` | Information about a TTL rule. | Associative array | No | `null` | `array( ... )` |
| `caching[n]['name']` | A human-readable name of the TTL rule. | String | No | `null` | `long_ttl` |
| `caching[n]['ttl']` | The TTL value, in seconds. | Integer | No | `null` | `604800` |
| `caching[n]['rules']` | List of rules that determine if this TTL should be applied to an asset. | Array of associative arrays | No | `null` | `array( ... )` |
| `caching[n]['rules'][n]` | Information about a TTL rule. | Associative array | No | `null` | `array( ... )` |
| `caching[n]['rules'][n]['name']` | A human-readable name of the TTL rule. | No | `null` | `images` | 
| `caching[n]['rules'][n]['requestUrl']` | The request URL this rule should match (regex supported). | String | No | `null` | `^/images/.+$` |
| `restrictions` | List of restrictions on where the service can be accessed from. | Array of associative arrays | No | `null` | `array( ... )` |
| `restrictions[n]` | Information about an access restriction. | Associative array | No | `null` | `array( ... )` |
| `restrictions[n]['name']` | A human-readable name of the restriction. | String | No | `null` | `affiliate_sites_only` |
| `restrictions[n]['rules']` | List of restriction rules. | Array of associative arrays | No | `null` | `array( ... )` |
| `restrictions[n]['rules'][n]` | Information about a restriction rule. | Associative array | No | `null` | `array( ... )` |
| `restrictions[n]['rules'][n]['name']` | A human-readable name of the restriction rule. | String | No | `null` | `Wile E. Coyote's site` |
| `restrictions[n]['rules'][n]['referrer']` | The domain from which the service can be accessed. | String | No | `null` | `www.wilecoyote.com` |

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
));
```

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

You can retrieve a specific service by using that service's ID, as shown in the following example:

```php
$service = $cdnService->getService('0e09ad12-2bfe-4607-80fd-116fa68d9c79');
/** @var $service OpenCloud\CDN\Resource\Service **/
```

[ [Get the executable PHP script for this example](/samples/CDN/get-service.php) ]

### Update a service

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | A human-readable name for the service. This name must be unique. | String | Yes | - | `acme_site` |
| `flavorId` | The ID of the flavor to use for this service. | String | Yes | - | `cdn` |
| `domains` | List of domain for your service. | Array of associative arrays | Yes | - | `array( ... )` |
| `domains[n]` | Information about a domain for your service. | Associative array | Yes | - | `array( ... )` |
| `domains[n]['domain']` | The domain name for your service. | String | Yes | - | 'www.acme.com' |
| `domains[n]['protocol']` | The protocol used by your service web site, `http` or `https`. | String | No | `http` | `http` |
| `origins` | List of origin servers for your service.  | Array of associative arrays | Yes | - | `array( ... )` |
| `origins[n]` | Information about an origin server for your service. | Associative array | Yes | - | `array( ... )` |
| `origins[n]['origin']` | The origin server address, from where the CDN will pull your web site's assets. | String | Yes | - | `origin.acme.com` |
| `origins[n]['origin']['port']` | The origin server's port. | Integer | No | 80 | `8080` |
| `origins[n]['origin']['ssl']` | Whether origin server uses SSL. | Boolean | No | `false` | `true` |
| `origins[n]['origin']['rules']` | List of rules defining the conditions when this origin should be accessed. | Array of associative arrays | No | `null` | `array( ... )` |
| `origins[n]['origin']['rules'][n]` | Information about an access rule. | Associative array | No | `null` | `array( ... )` |
| `origins[n]['origin']['rules'][n]['name']` | A human-readable name of the rule. | String | No | `null` | `images` |
| `origins[n]['origin']['rules'][n]['requestUrl']` | The request URL this rule should match (regex supported). | String | No | `null` | `^/images/.+$` |
| `caching` | List of TTL rules for assets of this service. | Array of associative arrays | No | `null` | `array( ... )` |
| `caching[n]` | Information about a TTL rule. | Associative array | No | `null` | `array( ... )` |
| `caching[n]['name']` | A human-readable name of the TTL rule. | String | No | `null` | `long_ttl` |
| `caching[n]['ttl']` | The TTL value, in seconds. | Integer | No | `null` | `604800` |
| `caching[n]['rules']` | List of rules that determine if this TTL should be applied to an asset. | Array of associative arrays | No | `null` | `array( ... )` |
| `caching[n]['rules'][n]` | Information about a TTL rule. | Associative array | No | `null` | `array( ... )` |
| `caching[n]['rules'][n]['name']` | A human-readable name of the TTL rule. | No | `null` | `images` | 
| `caching[n]['rules'][n]['requestUrl']` | The request URL this rule should match (regex supported). | String | No | `null` | `^/images/.+$` |
| `restrictions` | List of restrictions on where the service can be accessed from. | Array of associative arrays | No | `null` | `array( ... )` |
| `restrictions[n]` | Information about an access restriction. | Associative array | No | `null` | `array( ... )` |
| `restrictions[n]['name']` | A human-readable name of the restriction. | String | No | `null` | `affiliate_sites_only` |
| `restrictions[n]['rules']` | List of restriction rules. | Array of associative arrays | No | `null` | `array( ... )` |
| `restrictions[n]['rules'][n]` | Information about a restriction rule. | Associative array | No | `null` | `array( ... )` |
| `restrictions[n]['rules'][n]['name']` | A human-readable name of the restriction rule. | String | No | `null` | `Wile E. Coyote's site` |
| `restrictions[n]['rules'][n]['referrer']` | The domain from which the service can be accessed. | String | No | `null` | `www.wilecoyote.com` |

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

## Service Assets
A service will have its assets distributed and cached across a CDN's edge nodes.

### Purge all cached service assets

You can purge all cached assets of a service as shown in the following example:

```php
$service->purgeAssets();
```

[ [Get the executable PHP script for this example](/samples/CDN/purge-cached-service-assets.php) ]

### Purge a specific cached service asset

You can purge a specific asset of a service by providing its relative URL, as shown in the following example:

```php
$service->purgeAssets('/images/logo.png');
```

[ [Get the executable PHP script for this example](/samples/CDN/purge-cached-service-asset.php) ]

## Flavors

A flavor is a configuration option. A flavor enables you to choose from a generic setting that is powered by one or more CDN providers.

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
