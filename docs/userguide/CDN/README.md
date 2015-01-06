# CDN

**CDN** is a service that you can use to manage your CDN-enabled domains and the origins and assets associated with those domains.

## Concepts

To use the CDN service effectively, you should understand the following key concepts:

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

## Getting started

### 1. Instantiate an OpenStack or Rackspace client.

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

### 2. Obtain a CDN service object from the client.
All CDN operations are done via an _CDN service object_. To
instantiate this object, call the `cdnService` method on the `$client`
object. This method takes one argument:

| Position | Description | Data type | Required? | Default value | Example value |
| -------- | ----------- | ----------| --------- | ------------- | ------------- |
|  1       | Name of the service, as it appears in the service catalog | String | No | `null`; automatically determined when possible | `rackCDN` |


```php
$cdnService = $client->cdnService();
```

### 3. Create a service (to represent your web application).
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

## Next steps

Once you have created a service, there is more you can do with it. See [complete user guide for CDN](USERGUIDE.md).