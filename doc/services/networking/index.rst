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
  security-groups
  security-group-rules

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

  security group
    A security group is a named container for security group rules.

  security group rule
    A security group rule provides users the ability to specify the types of
    traffic that are allowed to pass through to and from ports on a virtual
    server instance.


Further links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/networks/api/v2/cn-gettingstarted/content/ch_preface.html>`_
- `API Developer Guide <http://docs.rackspace.com/networks/api/v2/cn-devguide/content/ch_preface.html>`_
- `API release history <http://docs.rackspace.com/networks/api/v2/cn-releasenotes/content/ch_preface.html>`_
