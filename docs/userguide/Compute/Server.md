# Servers

## Intro

A server is a virtual machine instance in the Cloud Servers environment.

## Setup

Server objects are instantiated from the Compute service. For more details, see the [Service](Service.md) docs.

## Get server

The easiest way to retrieve a specific server is by its unique ID:

```php
$serverId = 'ef08aa7a-b5e4-4bb8-86df-5ac56230f841';
$server   = $service->server($serverId);
```

## List servers

You can list servers in two different ways: 

* return an _overview_ of each server (ID, name and links)
* return _detailed information_ for each server

Knowing which option to use might help save unnecessary bandwidth and reduce latency. 

```php
// overview
$servers = $service->serverList();

// detailed
$servers = $service->serverList(true);
```

### URL parameters for filtering servers

Name|Description|Type
---|---|---
image|The image ID|string
flavor|The flavor ID|string
name|The server name|string
status|The server status. Servers contain a status attribute that indicates the current server state. You can filter on the server status when you complete a list servers request, and the server status is returned in the response body. For a full list, please consult `OpenCloud\Compute\Constants\ServerState`|string
changes-since|Value for checking for changes since a previous request|A valid ISO 8601 dateTime (2011-01-24T17:08Z)
RAX-SI:image_schedule|If scheduled images enabled or not. If the value is TRUE, the list contains all servers that have an image schedule resource set on them. If the value is set to FALSE, the list contains all servers that do not have an image schedule.|bool

## Create server

There are a few parameter requirements when creating a server:

* **name** - needs to be a string;
- **flavor** - a `OpenCloud\Compute\Resource\Flavor` object, that is populated with the values of a real API flavor;
* **image** - a `OpenCloud\Compute\Resource\Image` object, that is populated with the values of a real API image;
* **networks** - an array of `OpenCloud\Compute\Resource\Network` objects which represent which networks you compute instance will be placed in.

Firstly we need to find our flavor and image using their UUIDs. For more information about these concepts, including how to find flavor/image UUIDs, please consult §§ 3-4 in the [Getting Started guide](https://github.com/rackspace/php-opencloud/blob/master/docs/getting-started.md#3-select-your-server-image).

```php
$ubuntuImage = $compute->image('868a0966-0553-42fe-b8b3-5cadc0e0b3c5');
$twoGbFlavor = $compute->flavor('4');
```

Now we're ready to create our instance:

```php
use OpenCloud\Compute\Constants\Network;

$server = $compute->server();

try {
    $response = $server->create(array(
        'name'     => 'My lovely server',
        'image'    => $ubuntuImage,
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

    echo sprintf('Status: %s\nBody: %s\nHeaders: %s', $statusCode, $responseBody, implode(', ', $headers);
}
```

It's always best to be defensive when executing functionality over HTTP; you can achieve this best by wrapping calls in a try/catch block. It allows you to debug your failed operations in a graceful and efficient manner. 

### Create parameters

Name|Description|Type|Required
---|---|---|---
name|The server name. The name that you specify in a create request becomes the initial host name of the server. After the server is built, if you change the server name in the API or change the host name directly, the names are not kept in sync.|string|Yes
flavor|A populated `OpenCloud\Compute\Resource\Flavor` object representing your chosen flavor|object|Yes
image|A populated `OpenCloud\Compute\Resource\Image` object representing your chosen image|object|Yes
OS-DCF:diskConfig|The disk configuration value. You can use two options: `AUTO` or `MANUAL`. <br><br>`AUTO` means the server is built with a single partition the size of the target flavor disk. The file system is automatically adjusted to fit the entire partition. This keeps things simple and automated. `AUTO` is valid only for images and servers with a single partition that use the EXT3 file system. This is the default setting for applicable Rackspace base images.<br><br>`MANUAL` means the server is built using whatever partition scheme and file system is in the source image. If the target flavor disk is larger, the remaining disk space is left unpartitioned. This enables images to have non-EXT3 file systems, multiple partitions, and so on, and enables you to manage the disk configuration.|string|No
networks|An array of populated `OpenCloud\Compute\Resource\Network` objects that indicate which networks your instance resides in.|array|Yes
metadata|An array of arbitrary data (key-value pairs) that adds additional meaning to your server.|array|No
keypair|You can install a registered keypair onto your newly created instance, thereby providing scope for keypair-based authentication.|array|No
personality|Files that you can upload to your newly created instance's filesystem.|array|No

### Creating a server with keypairs

Please see the [Keypair](Keypair.md) docs for more information.

### Creating a server with personality files

Before you execute the create operation, you can add "personality" files to your `OpenCloud\Compute\Resource\Server` object. These files are structured as a flat array.

```php
$server->addFile('/var/test_file', 'FILE CONTENT');
```

As you can see, the first parameter represents the filename, and the second is a string representation of its content. When the server is created these files will be created on its local filesystem. For more information about server personality files, please consult the [official documentation](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Server_Personality-d1e2543.html).

## Update server

You can update certain attributes of an existing server instance. These attributes are detailed in the next section.

```php
$server->update(array(
   'name' => 'NEW SERVER NAME'
));
```

### Updatable attributes

name|description
---|---
name|The name of the server. If you edit the server name, the server host name does not change. Also, server names are not guaranteed to be unique.
accessIPv4|The IP version 4 address.
accessIPv6|The IP version 6 address.

## Delete server

```php
$server->delete();
```