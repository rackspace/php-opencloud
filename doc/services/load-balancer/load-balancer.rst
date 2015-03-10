Load Balancer
=============

.. note::

  Many of the examples in this document use two cloud servers as nodes for
  the load balancer. The variables ``$serverOne`` and ``$serverTwo`` refer
  to these two cloud servers.


Create Load Balancer
--------------------

The first step is to instantiate an empty object, like so:

.. code-block:: php

  $loadBalancer = $service->loadBalancer();

In essence, all a load balancer does is evenly distribute traffic between
various back-end nodes - which can be Compute or Database instances. So we will
need to add a few when creating our load balancer:

.. code-block:: php

  $serverOneNode = $loadBalancer->node();
  $serverOneNode->address = $serverOne->addresses->private[0]->addr;
  $serverOneNode->port = 8080;
  $serverOneNode->condition = 'ENABLED';

  $serverTwoNode = $loadBalancer->node();
  $serverTwoNode->address = $serverTwo->addresses->private[0]->addr;
  $serverTwoNode->port = 8080;
  $serverTwoNode->condition = 'ENABLED';


All that remains is apply final configuration touches, such as name and the
port number, before submitting to the API:

.. code-block:: php

  $loadBalancer->addVirtualIp('PUBLIC');
  $loadBalancer->create(array(
      'name'      => 'My load balancer',
      'port'      => 80,
      'protocol'  => 'HTTP',
      'nodes'     => array($serverOneNode, $serverTwoNode),
      'algorithm' => 'ROUND_ROBIN',
  ));

For a full list of available `protocols <#protocols>`_ and `algorithms <#algorithms>`_
please see the sections below.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/LoadBalancer/create-lb.php>`_


Get Load Balancer Details
-------------------------

You can retrieve a single load balancer's details by using its ID:

.. code-block:: php

  /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/
  $loadBalancer = $service->loadBalancer('{loadBalancerId}');


List Load Balancers
-------------------

You can retrieve a list of all your load balancers:

.. code-block:: php

  $loadBalancers = $service->loadBalancerList();

  foreach ($loadBalancers as $loadBalancer) {
      /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/LoadBalancer/list-load-balancers.php>`_


Update a Load Balancer
----------------------

You can update one or more of the following load balancer attributes:

-  ``name``: The name of the load balancer
-  ``algorithm``: The algorithm used by the load balancer to distribute
   traffic amongst its nodes. See also: `Load balancing
   algorithms <#algorithms>`__.
-  ``protocol``: The network protocol used by traffic coming in to the
   load balancer. See also: `Protocols <#protocols>`__.
-  ``port``: The network port on which the load balancer listens for
   incoming traffic.
-  ``halfClosed``: Enable or Disable Half-Closed support for the load
   balancer.
-  ``timeout``: The timeout value for the load balancer to communicate
   with its nodes.
-  ``httpsRedirect``: Enable or disable HTTP to HTTPS redirection for
   the load balancer. When enabled, any HTTP request will return status
   code 301 (Moved Permanently), and the requestor will be redirected to
   the requested URL via the HTTPS protocol on port 443. For example,
   http://example.com/page.html would be redirected to https://
   example.com/page.html. Only available for HTTPS protocol (``port`` =
   443), or HTTP Protocol with a properly configured SSL Termination
   (\`secureTrafficOnly=true, securePort=443). See also: `SSL
   Termination <#ssl-termination>`__.

.. code-block:: php

  $loadBalancer->update(array(
      'name'      => 'New name',
      'algorithm' => 'ROUND_ROBIN'
  ));


Remove Load Balancer
~~~~~~~~~~~~~~~~~~~~

When you no longer have a need for the load balancer, you can remove it:

.. code-block:: php

  $loadBalancer->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/LoadBalancer/delete-lb.php>`_


Protocols
---------

When a load balancer is created a network protocol must be specified.
This network protocol should be based on the network protocol of the
back-end service being load balanced. Common protocols are ``HTTP``, ``HTTPS``
and ``MYSQL``. A full list is available `here <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/protocols.html>`_.

List Load Balancing Protocols
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can list all supported network protocols like so:

.. code-block:: php

  $protocols = $service->protocolList();

  foreach ($protocols as $protocol) {
      /** @var $protocol OpenCloud\LoadBalancer\Resource\Protocol **/
  }


Algorithms
----------

Load balancers use an **algorithm** to determine how incoming traffic is
distributed amongst the back-end nodes. A full list is available `here
<http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/Algorithms-d1e4367.html>`_.

List Load Balancing Algorithms
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can programmatically list all supported load balancing algorithms:

.. code-block:: php

  $algorithms = $service->algorithmList();

  foreach ($algorithms as $algorithm) {
      /** @var $algorithm OpenCloud\LoadBalancer\Resource\Algorithm **/
  }
