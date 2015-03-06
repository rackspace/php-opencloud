Networking v2
=============

.. include:: ../common/clients.sample.rst

Networking service
~~~~~~~~~~~~~~~~~~

Now to instantiate the Networking service:

.. code-block:: php

  $service = $client->networkingService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  networks
  subnets
  ports

Glossary
--------

.. glossary::

  network
    A network is an isolated virtual layer-2 broadcast domain that is typically
    reserved for the tenant who created it unless you configure the network to
    be shared. The network is the main entity in the Networking service. Ports
    and subnets are always associated with a network.

  subnet
    A subnet represents an IP address block that can be used to assign IP
    addresses to virtual instances (such as servers created using the Compute
    service). Each subnet must have a CIDR and must be associated with a network.

  port
    A port represents a virtual switch port on a logical network switch.
    Virtual instances (such as servers created using the Compute service)
    attach their interfaces into ports. The port also defines the MAC address
    and the IP address(es) to be assigned to the interfaces plugged into them.
    When IP addresses are associated to a port, this also implies the port is
    associated with a subet, as the IP address is taken from the allocation
    pool for a specific subnet.
