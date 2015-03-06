Load Balancer v1
================

.. include:: ../common/rs-only.sample.rst


Load Balancer service
~~~~~~~~~~~~~~~~~~~~~

Now to instantiate the Load Balancer service:

.. code-block:: php

  $service = $client->loadBalancerService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  load-balancer
  nodes
  virtual-ips
  access
  caching
  errors
  logging
  monitors
  metadata
  sessions
  ssl
  stats


Glossary
--------

  allowed domain
    Allowed domains are a restricted set of domain names that are allowed to add
    load balancer nodes.

  content caching
    When content caching is enabled on a load balancer, recently-accessed files
    are stored on the load balancer for easy retrieval by web clients. Requests to
    the load balancer for these files are serviced by the load balancer itself,
    which reduces load off its back-end nodes and improves response times as well.

  health monitor
    The load balancing service includes a health monitoring operation which
    periodically checks your back-end nodes to ensure they are responding
    correctly. If a node is not responding, it is removed from rotation until the
    health monitor determines that the node is functional. In addition to being
    performed periodically, the health check also is performed against every node
    that is added to ensure that the node is operating properly before allowing it
    to service traffic. Only one health monitor is allowed to be enabled on a load
    balancer at a time.

  load balancer
    A load balancer is a device that distributes incoming network
    traffic amongst multiple back-end systems. These back-end systems are
    called the nodes of the load balancer.

  metadata
    Metadata can be associated with each load balancer and each node for the
    client's personal use. It is defined using key-value pairs where the key
    and value consist of alphanumeric characters. A key is unique per load
    balancer.

  node
    A node is a backend device that provides a service on specified IP and port.
    An example of a load balancer node might be a web server serving HTTP
    traffic on port 8080. A load balancer typically has multiple nodes attached
    to it so it can distribute incoming network traffic amongst them.

  session persistence
    Session persistence is a feature of the load balancing service that forces
    multiple requests, of the same protocol, from clients to be directed to the
    same node. This is common with many web applications that do not inherently
    share application state between back-end servers.

  virtual IP
    A virtual IP (VIP) makes a load balancer accessible by clients. The
    load balancing service supports either a public VIP address
    (``PUBLIC``), routable on the public Internet, or a ServiceNet VIP
    address (``SERVICENET``), routable only within the region in which the
    load balancer resides.


Further Links
-------------

- `API Developer Guide <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/Overview-d1e82.html>`_
- `API release history <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-getting-started/content/DB_Doc_Change_History.html>`_
