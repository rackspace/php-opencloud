Working with Servers
====================

A server is a virtual machine instance that is managed by OpenStack Nova. It is
represented by the `OpenCloud\Compute\Resource\Server` class.

## Setup

In order to use a `Server` object, you must create the Compute service first:

```php
$service = $client->computeService('cloudServersOpenStack', 'ORD');
```

For more information about setting up client objects, see the
[client documentation](Clients.md). For more information about service objects,
including a full list of expected arguments, see the
[service documentation](Services.md).

## Get an existing server

To retrieve an existing server, all you need is its unique ID:

```php
/** @param $server OpenCloud\Compute\Resource\Server */
$server = $server->server('<server_id>');
```

## Create a new server

A server requires both a [Flavor object](flavors.md) and an
[Image object](images.md) to be created. In addition, a server requires a name:

```php
// New instance of OpenCloud\Compute\Resource\Server
$server = $service->server();

/** @param $server OpenCloud\Image\Resource\ImageInterface */
$image = $service->image('<image_id>');

/** @param $server OpenCloud\Compute\Resource\Flavor */
$flavor = $service->flavor('<flavor_id>');

// Send to API
$server->create(array(
    'name'   => 'New server',
    'flavor' => $flavor,
    'image' => $image
));
```

Server builds typically take under 5 minutes to complete (depending upon the size
of the server). However, the initial response will return the server's ID as
well as the assigned root password:

```php
echo $server->adminPass;
```

(Note: it is not recommended that you print out the root password because of
security risks. This is only provided as an example.)

When you create a new server on the Rackspace public cloud, you can also
associate it with one or more isolated networks. For more information, see
[Working with Cloud Networks](networks.md).

### Rebuilding an existing server

"Rebuilding" a server is nearly identical to creating one; you must supply
an Image object. You can also change the server's name as part of the rebuild.
The primary difference between a create and a rebuild is that, in the rebuild,
the server's IP address(es) are retained (when the server is created, new IP
addresses are assigned).

To rebuild a server:

```php
$server->rebuild(array(
    'adminPass' => 'rootPassword',
    'name'      => 'A Bigger Server',
    'image'     => $compute->image('<image_id>')
));
```

### Updating a server

The `update()` method is very similar to `create()` except that the only
attributes of a server that you are permitted to update are its name and
the [access IP addresses](accessip.md).

```php
$server->update(array('accessIPv4' => '50.57.94.244'));
```

### Deleting a server

```php
$server->delete();
```

## Server actions

You can perform various actions on a server, such as rebooting it, resizing
it, or changing the root password.

### Setting the root password

Use the `setPassword()` method to change the root user's password:

```php
$server->setPassword('new password');
```

Note that it may take a few second for the new password to take effect. Also,
password restrictions (such as the minimum number of characters, numbers of
punctuation characters, and so forth) are enforced by the operating system and are
not always detectable by the Compute service. This means that, even though
the `setPassword()` method succeeds, the password may not be changed, and
there may not be any feedback to that effect.

### To reboot the server

You can perform either a *hard* reboot (this is like pulling the power cord
and then restarting) or a *soft* reboot (initiated by the operating system
and generally less disruptive than a hard reboot). A hard reboot is
performed by default:

```php
$server->reboot();                                  // hard reboot
$server->reboot(ServerState::REBOOT_STATE_HARD);    // also a hard reboot
$server->reboot(ServerState::REBOOT_STATE_SOFT);    // a soft reboot
```

If the server is "hung," or unresponsive, a hard reboot may sometimes be
the only way to access the server.

### To resize the server

A server can be resized by providing a new [Flavor object](flavors.md):

```php
$server->resize($compute->flavor(5));
```

Once the resize completes (check the `$server->status`), you can either
confirm it:

```php
$server->resizeConfirm();
```

or revert it back to the original size:

```php
$server->resizeRevert();
```

### To rescue/unrescue a server

In rescue mode, a server is rebuilt to a pristine state and the existing
filesystem is mounted so that you can edit files and diagnose issues.
See [this document](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/rescue_mode.html)
for more details.

Put server into rescue mode:

```php
$password = $server->rescue();
```

The `$password` is the assigned root password of the rescue server.

Take server out of rescue mode:

```php
$server->unrescue();
```

This restores the server to its original state (plus any changes you may have
made while it was in rescue mode).

## What next?

* See also [Working with Networks](networks.md).
* To learn about dynamic 
  volume creation and assignment, see 
  [Working with Volumes](volumes.md).

