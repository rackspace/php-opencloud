Networking
==========

**Networking** is a service that you can use to create virtual networks
and attach cloud devices such as servers to these networks.

Concepts
--------

Concepts
--------

To use the Networking service effectively, you should understand the
following key concepts:

-  **Network**: A network is an isolated virtual layer-2 broadcast
   domain that is typically reserved for the tenant who created it
   unless you configure the network to be shared. The network is the
   main entity in the Networking service. Ports and subnets are always
   associated with a network.

-  **Subnet**: A subnet represents an IP address block that can be used
   to assign IP addresses to virtual instances (such as servers created
   using the Compute service). Each subnet must have a CIDR and must be
   associated with a network.

-  **Port**: A port represents a virtual switch port on a logical
   network switch. Virtual instances (such as servers created using the
   Compute service) attach their interfaces into ports. The port also
   defines the MAC address and the IP address(es) to be assigned to the
   interfaces plugged into them. When IP addresses are associated to a
   port, this also implies the port is associated with a subet, as the
   IP address is taken from the allocation pool for a specific subnet.

Getting started
---------------

1. Instantiate an OpenStack or Rackspace client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To use the Networking service, you must first instantiate a
``OpenStack`` or ``Rackspace`` client object.

-  If you are working with an OpenStack cloud, instantiate an
   ``OpenCloud\OpenStack`` client as follows:

   .. code:: php

       use OpenCloud\OpenStack;

       $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
           'username' => '<YOUR OPENSTACK CLOUD ACCOUNT USERNAME>',
           'password' => '<YOUR OPENSTACK CLOUD ACCOUNT PASSWORD>'
       ));

-  If you are working with the Rackspace cloud, instantiate a
   ``OpenCloud\Rackspace`` client as follows:

   .. code:: php

       use OpenCloud\Rackspace;

       $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
           'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
           'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
        ));

2. Obtain an Networking service object from the client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

All Networking operations are done via an *networking service object*.
To instantiate this object, call the ``networkingService`` method on the
``$client`` object. This method takes two arguments:

+------------+-------------------------------------------------------------+-------------+-------------+----------------------------------------------------+---------------------+
| Position   | Description                                                 | Data type   | Required?   | Default value                                      | Example value       |
+============+=============================================================+=============+=============+====================================================+=====================+
| 1          | Name of the service, as it appears in the service catalog   | String      | No          | ``null``; automatically determined when possible   | ``cloudNetworks``   |
+------------+-------------------------------------------------------------+-------------+-------------+----------------------------------------------------+---------------------+
| 2          | Cloud region                                                | String      | Yes         | -                                                  | ``DFW``             |
+------------+-------------------------------------------------------------+-------------+-------------+----------------------------------------------------+---------------------+

.. code:: php

    $region = '<CLOUD REGION NAME>';
    $networkingService = $client->networkingService(null, $region);

Any networks, subnets, and ports created with this
``$networkingService`` instance will be stored in the cloud region
specified by ``$region``.

3. Create a network.
~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $network = $networkingService->createNetwork(array(
        'name' => 'My private backend network'
    ));

[ `Get the executable PHP script for this
example </samples/Networking/create-network.php>`__ ]

Next steps
----------

Once you have created a network, there is more you can do with it. See
`complete user guide for networking <USERGUIDE.md>`__.
