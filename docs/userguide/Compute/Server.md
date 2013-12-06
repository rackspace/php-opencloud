# Servers

## Intro

A server is a virtual machine instance in the Cloud Servers environment.

## Setup

Please consult the [service documentation](Service.md) for more information about setting up a Compute service object.

## Get server

```php
$serverId = 'ef08aa7a-b5e4-4bb8-86df-5ac56230f841';
$server   = $service->server($serverId);
```

## List servers

You can list servers in two different ways: one operation returns an overview for each server (ID, name and links); the other returns detailed information for each server.

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

```php
$server = $service->server();

$server->addFile('/var/test1', 'TEST 1');
$server->addFile('/var/test2', 'TEST 2');
$server->create(array(
    'name'     => $this->prepend(self::SERVER_NAME . time()),
    'image'    => $centos,
    'flavor'   => $flavorList->first(),
    'networks' => array(
        $this->getService()->network(Network::RAX_PUBLIC),
        $this->getService()->network(Network::RAX_PRIVATE)
    ),
    "OS-DCF:diskConfig" => "AUTO"
));
```

### Create parameters

Name|Description|Type|Required
---|---|---|---
name|The server name. The name that you specify in a create request becomes the initial host name of the server. After the server is built, if you change the server name in the API or change the host name directly, the names are not kept in sync.|string|Yes
flavorRef|The flavor ID|string|Yes
imageRef|The image ID|string|Yes
OS-DCF:diskConfig|The disk configuration value. You can use two options. `AUTO` means the server is built with a single partition the size of the target flavor disk. The file system is automatically adjusted to fit the entire partition. This keeps things simple and automated. `AUTO` is valid only for images and servers with a single partition that use the EXT3 file system. This is the default setting for applicable Rackspace base images.

MANUAL: The server is built using whatever partition scheme and file system is in the source image. If the target flavor disk is larger, the remaining disk space is left unpartitioned. This enables images to have non-EXT3 file systems, multiple partitions, and so on, and enables you to manage the disk configuration.

## Update server

## Delete server