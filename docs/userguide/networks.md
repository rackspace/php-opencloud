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

<a name="create"></a>
### Create a network

A Cloud Network is identified by a *label* and must be defined with
a network *CIDR* address range. For example, we can create a network
used by our backend servers on the 192.168.0.0 network like this:

	// assume $compute is our Compute service
	$backend = $compute->network();		// new, empty network object
	$backend->create(array(
		'label' => 'Backend Network',	// label it
		'cidr' => '192.168.0.0/16'	// this provides the address range
    ));

The ID of the network is now available in `$backend->id`.

<a name="delete"></a>
### Delete a network

To delete a network, use the `delete()` method:

	$backend->delete();

Note that you cannot delete a network if there are still servers 
attached to it.

### Listing networks

The `Compute::networkList()` method returns a [Collection](collections.md) of
`Network` objects:

	$list = $compute->networkList();
	$list->sort('label');
	while($network = $list->next())
		printf("%s: %s\n", $network->id, $network->label);

This displays the ID and label of all your available networks
(including the `public` and `private` pseudo-networks).

<a name="server"></a>
## Create a server with an isolated network

When you create a new server, you can specify the networks to which
it is attached via the `networks` attribute in the `Server::create()`
method.

* If you create the server without specifying its `networks`, then it is created
  using the default `public` and `private` networks.
* If you create the server and specify `networks`, it is created with *only* the
  networks that you specify. That is, if you specify `networks`, then the
  `public` and `private` networks are *not* attached to the server unless you
  explicitly include them.

Example:

    use OpenCloud\Compute\Constants\Network;

	// assuming the $backend network we created above
	$server = $compute->server();			// create an empty server object
	$server->create(array(
		'name' => 'My Server',
		'flavor' => $compute->flavor(2),
		'image' => $compute->image('c195ef3b-9195-4474-b6f7-16e5bd86acd0'),
		'networks' => array(				// associate our networks
			$backend,
			$compute->network(Network::RAX_PUBLIC))));

In this example, the server `$server` is attached to the `$backend`
network that we created in the previous example. It is also attached
to the Rackspace `public` network (the Internet). However, it is
*not* attached to the Rackspace `private` network (ServiceNet).

The `networks` attribute is an array of `Network` objects (and not
just a UUID of the network). This example will fail:

	$server->create(array(
		...
		'networks' => array(Network::RAX_PUBLIC, Network::RAX_PRIVATE)));

because `Network::RAX_PUBLIC` and `Network::RAX_PRIVATE` are UUIDs, not networks. Use
the `Compute::network()` method to convert a UUID into a network:

	$server->create(array(
		...
		'networks' => array($compute->network(Network::RAX_PUBLIC),...)));

## What next?

Return to the [Table of Contents](toc.md).
