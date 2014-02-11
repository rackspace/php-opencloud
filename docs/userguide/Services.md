# Services

Both OpenStack and Rackspace are comprised of different services: Compute (Nova),
Object Storage (Swift), Queues (Marconi), etc.  Each of these services is
encapsulated in its own class which implements the
`OpenCloud\Common\Service\ServiceInterface` interface.

Each service class is nested in its own namespace:

Service|OpenStack codename|FQCN
---|---|---
Compute|Nova|`OpenCloud\Compute\Service`
Object Storage|Swift|`OpenCloud\ObjectStore\Service`
Identity|Keystone|`OpenCloud\Identity\Service`
Queues|Marconi|`OpenCloud\Queues\Service`

## Instantiating a service

The easiest way to instantiate a service, is using the convenient factory
methods of the client class. For more information about clients, see the
[client documentation](Clients.md).

To create a Compute service, for example:

```php
$service = $client->computeService('cloudServersOpenStack', 'DFW');
```

### Method arguments

As you will notice, each factory method accepts three arguments:

Position|Name|Required?|Type|Description
---|---|---|---|---
1|Service name|Yes|string|The name of the service as it appears in the [Service Catalog](https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/Clients.md#service-catalog)
2|Region|Yes|string|The region you want your service to operate in
3|URL type|No|enum|Each service has two different URL types for API transactions: a public URL (i.e. over the Internet) and a private URL (through Rackspace's private network). Private URL's are beneficial because they have lower latency and incur no bandwidth charges; the only condition is that you are communicating to the API from the **same geographic region** (i.e. from a Cloud Serve in the same region). <br><br>The accepted options you may pass in are either `OpenCloud\Common\Constants\Service::INTERNAL_URL` or `OpenCloud\Common\Constants\Service::PUBLIC_URL`.


## Differences between OpenStack and Rackspace

Not all of Rackspace's services are supported by OpenStack; examples include:
Cloud Databases, Auto Scale, Load Balancers, DNS, etc.

For this reason, if you want to use or interact with these Rackspace-only
services, you must do so by using the methods of the `OpenCloud\Rackspace`
client object and **not** the `OpenCloud\OpenStack` client.