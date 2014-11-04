Working with Cloud Networks
===========================

Rackspace Cloud Networks is a virtual "isolated network" product
based upon (though not strictly identical to) the [OpenStack
Quantum](http://quantum.openstack.org) project. With Cloud Networks,
you can create multiple isolated networks and associate them with
new Cloud Servers.  (You cannot add an isolated network to an
existing Cloud Server at this time, although that feature may be
available in the future.)

Since the network is a feature of the Cloud Servers product, you
use the `Compute::network()` method to create a new network, and
the `Compute::networkList()` method to retrieve information about
existing networks.

### Pseudo-networks

Rackspace has two *pseudo-networks* called `public` and `private`.
The `public` network represents the Internet, while the `private`
network represents the Rackspace internal ServiceNet (an infrastructure
network used to facilitate communication within a Rackspace data
center). These are called "pseudo-networks" because they are not
actually represented in the Quantum component, but have a special
representation that you must use if you want to associate your
server with them.

The `public` network is represented by the special UUID
`00000000-0000-0000-0000-000000000000` and the `private` network
by `11111111-1111-1111-1111-111111111111`. <b>php-opencloud</b>
provides the special constants `RAX_PUBLIC` and `RAX_PRIVATE` to
make using these networks easier (see [Create a server with an
isolated network](#server) below).


### Create a network

A Cloud Network is identified by a *label* and must be defined with
a network *CIDR* address range. For example, we can create a network
used by our backend servers on the 192.168.0.0 network like this:

```php
// Create instance of OpenCloud\Compute\Resource\Network
$network = $compute->network();

// Send to API
$network->create(array(
    'label' => 'Backend Network',
    'cidr'  => '192.168.0.0/16'
));
```

### Delete a network

```php
$network->delete();
```

Note that you cannot delete a network if there are still servers attached to it.

### Listing networks

```
$networks = $service->networkList();

foreach ($networks as $network) {
    /** @param $network OpenCloud\Compute\Resource\Network */
}
```

For more information about working with iterators, please see the
[iterators documentation](Iterators.md).

## Create a server with an isolated network

When you create a new server, you can specify the networks to which
it is attached via the `networks` attribute in the `Server::create()`
method:

use OpenCloud\Compute\Constants\Network;

// Create instance of OpenCloud\Compute\Resource\Server
$server = $compute->server();

// Send to API
$server->create(array(
    'name'     => 'My Server',
    'flavor'   => $compute->flavor('<flavor_id>'),
    'image'    => $compute->image('<image_id>'),
    'networks' => array(
        $network,
        $compute->network(Network::RAX_PUBLIC)
    )
));
```

In this example, the server `$server` is attached to the network that we
created in the previous example. It is also attached to the Rackspace `public`
network (the Internet). However, it is *not* attached to the Rackspace `private`
network (ServiceNet).

Note that the `networks` attribute is an array of `OpenCloud\Compute\Resource\Network`
objects.