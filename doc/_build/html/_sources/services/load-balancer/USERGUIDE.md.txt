The Complete User Guide to Load Balancers
=========================================

Prerequisites
-------------

Client
~~~~~~

To use the load balancers service, you must first instantiate a
``Rackspace`` client object.

.. code:: php

    use OpenCloud\Rackspace;

    $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
    ));

Load Balancer Service
~~~~~~~~~~~~~~~~~~~~~

All operations on load balancers are done via a load balancer service
object.

.. code:: php

    $loadBalancerService = $client->loadBalancerService('cloudLoadBalancers', 'DFW');

Cloud Servers
~~~~~~~~~~~~~

Many of the examples in this document use two cloud servers as nodes for
the load balancer. The variables ``$serverOne`` and ``$serverTwo`` refer
to these two cloud servers.

Load Balancers
--------------

A **load balancer** is a device that distributes incoming network
traffic amongst multiple back-end systems. These back-end systems are
called the **nodes** of the load balancer.

Create Load Balancer
~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $loadBalancer = $loadBalancerService->loadBalancer();

    $serverOneNode = $loadBalancer->node();
    $serverOneNode->address = $serverOne->addresses->private[0]->addr;
    $serverOneNode->port = 8080;
    $serverOneNode->condition = 'ENABLED';

    $serverTwoNode = $loadBalancer->node();
    $serverTwoNode->address = $serverTwo->addresses->private[0]->addr;
    $serverTwoNode->port = 8080;
    $serverTwoNode->condition = 'ENABLED';

    $loadBalancer->addVirtualIp('PUBLIC');
    $loadBalancer->create(array(
        'name'     => 'My smart load balancer',
        'port'     => 80,
        'protocol' => 'HTTP',
        'nodes'    => array($serverOneNode, $serverTwoNode)
    ));

List Load Balancer Details
~~~~~~~~~~~~~~~~~~~~~~~~~~

You can retrieve a single load balancer's details by using its ID.

.. code:: php

    $loadBalancer = $loadBalancerService->loadBalancer('254889');

    /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/

List Load Balancers
~~~~~~~~~~~~~~~~~~~

You can retrieve a list of all your load balancers. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $loadBalancers = $loadBalancerService->loadBalancerList();
    foreach ($loadBalancers as $loadBalancer) {
        /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/
    }

Update Load Balancer Attributes
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

Updating a single attribute of a load balancer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    $loadBalancer->update(array(
        'name' => 'New name'
    ));

Updating multiple attributes of a load balancer
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    $loadBalancer->update(array(
        'name'      => 'New name',
        'algorithm' => 'ROUND_ROBIN'
    ));

Remove Load Balancer
~~~~~~~~~~~~~~~~~~~~

When you no longer have a need for the load balancer, you can remove it.

.. code:: php

    $loadBalancer->delete(); 

Nodes
-----

A **node** is a backend device that provides a service on specified IP
and port. An example of a load balancer node might be a web server
serving HTTP traffic on port 8080.

A load balancer typically has multiple nodes attached to it so it can
distribute incoming network traffic amongst them.

List Nodes
~~~~~~~~~~

You can list the nodes attached to a load balancer. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $nodes = $loadBalancer->nodeList();
    foreach ($nodes as $node) {
        /** @var $node OpenCloud\LoadBalancer\Resource\Node **/
    }

Add Nodes
~~~~~~~~~

You can attach additional nodes to a load balancer. Assume
``$loadBalancer`` already has two nodes attached to it - ``$serverOne``
and ``$serverTwo`` - and you want to attach a third node to it, say
``$serverThree``, which provides a service on port 8080.

**Important:** Remember to call ``$loadBalancer->addNodes()`` after all
the calls to ``$loadBalancer->addNode()`` as shown below.

.. code:: php

    $address = $serverThree->addresses->private[0]->addr;
    $loadBalancer->addNode($address, 8080);
    $loadBalancer->addNodes();

The ``addNode`` method accepts three more optional parameters, in
addition to the two shown above:

| Position \| Description \| Data type \| Required? \| Default value \|
| ----------- \| --------------- \| --------------\| -------------- \|
----------------- \|
|  1 \| IP address of node \| String \| Yes \| - \|
|  2 \| Port used by node's service \| Integer \| Yes \| - \|
|  3 \| Starting condition of node:
|  4 \| Type of node to add:
|  5 \| Weight, between 1 and 100, given to node when distributing
traffic using either the ``WEIGHTED_ROUND_ROBIN`` or the
``WEIGHTED_LEAST_CONNECTIONS`` load balancing algorithm. \| Integer \|
No \| 1 \|

Modify Nodes
~~~~~~~~~~~~

You can modify one or more of the following node attributes:

-  ``condition``: The condition of the load balancer:

   -  ``ENABLED`` – Node is ready to receive traffic from the load
      balancer.
   -  ``DISABLED`` – Node should not receive traffic from the load
      balancer.
   -  ``DRAINING`` – Node should process any traffic it is already
      receiving but should not receive any further traffic from the load
      balancer.

-  ``type``: The type of the node:

   -  ``PRIMARY`` – Nodes defined as PRIMARY are in the normal rotation
      to receive traffic from the load balancer.
   -  ``SECONDARY`` – Nodes defined as SECONDARY are only in the
      rotation to receive traffic from the load balancer when all the
      primary nodes fail.

-  ``weight``: The weight, between 1 and 100, given to node when
   distributing traffic using either the ``WEIGHTED_ROUND_ROBIN`` or the
   ``WEIGHTED_LEAST_CONNECTIONS`` load balancing algorithm.

Modifying a single attribute of a node
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    use OpenCloud\LoadBalancer\Enum\NodeCondition;

    $node->update(array(
        'condition' => NodeCondition::DISABLED
    ));

Modifying multiple attributes of a node
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    use OpenCloud\LoadBalancer\Enum\NodeCondition;
    use OpenCloud\LoadBalancer\Enum\NodeType;

    $node->update(array(
        'condition' => NodeCondition::DISABLED,
        'type'      => NodeType::SECONDARY
    ));

Remove Nodes
~~~~~~~~~~~~

There are two ways to remove a node.

Given an ``OpenCloud\LoadBalancer\Resource\Node`` instance
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    $node->delete();

Given an ``OpenCloud\LoadBalancer\Resource\LoadBalancer`` instance and a node ID
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. code:: php

    $loadBalancer->removeNode(490639);

The ``removeNode`` method, as shown above, accepts the following
arguments:

+------------+---------------+-------------+-------------+-----------------+
| Position   | Description   | Data type   | Required?   | Default value   |
+============+===============+=============+=============+=================+
| 1          | ID of node    | Integer     | Yes         | -               |
+------------+---------------+-------------+-------------+-----------------+

View Node Service Events
~~~~~~~~~~~~~~~~~~~~~~~~

You can view events associated with the activity between a node and a
load balancer. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $nodeEvents = $loadBalancer->nodeEventList();
    foreach ($nodeEvents as $nodeEvent) {
        /** @var $nodeEvent OpenCloud\LoadBalancer\Resource\NodeEvent **/
    }

Virtual IPs
-----------

A **virtual IP (VIP)** makes a load balancer accessible by clients. The
load balancing service supports either a public VIP address
(``PUBLIC``), routable on the public Internet, or a ServiceNet VIP
address (``SERVICENET``), routable only within the region in which the
load balancer resides.

List Virtual IPs
~~~~~~~~~~~~~~~~

You can list the VIPs associated with a load balancer. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $vips = $loadBalancer->virtualIpList();
    foreach ($vips as $vip) {
        /** @var $vip of OpenCloud\LoadBalancer\Resource\VirtualIp **/
    }

Add Virtual IPv6
~~~~~~~~~~~~~~~~

You can add additional IPv6 VIPs to a load balancer.

.. code:: php

    use OpenCloud\LoadBalancer\Enum\IpType;

    $loadBalancer->addVirtualIp(IpType::PUBLIC, 6);

The ``addVirtualIp`` method, as shown above, accepts the following
arguments:

| Position \| Description \| Data type \| Required? \| Default value \|
| ----------- \| --------------- \| -------------- \|-------------- \|
----------------- \|
|  1 \| Type of VIP:
|  2 \| IP version: Must be ``6`` \| Integer \| Yes \| - \|

Remove Virtual IPs
~~~~~~~~~~~~~~~~~~

You can remove a VIP from a load balancer.

.. code:: php

    $vip->remove();

Please note that a load balancer must have at least one VIP associated
with it. If you try to remove a load balancer's last VIP, a
``ClientErrorResponseException`` will be thrown.

Algorithms
----------

Load balancers use an **algorithm** to determine how incoming traffic is
distributed amongst the back-end nodes.

List Load Balancing Algorithms
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can list all supported load balancing algorithms using a load
balancer service object. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $algorithms = $loadBalancerService->algorithmList();
    foreach ($algorithms as $algorithm) {
        /** @var $algorithm OpenCloud\LoadBalancer\Resource\Algorithm **/
    }

Protocols
---------

When a load balancer is created a network protocol must be specified.
This network protocol should be based on the network protocol of the
back-end service being load balanced.

List Load Balancing Protocols
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can list all supported network protocols using a load balancer
service object. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $protocols = $loadBalancerService->protocolList();
    foreach ($protocols as $protocol) {
        /** @var $protocol OpenCloud\LoadBalancer\Resource\Protocol **/
    }

Session Persistence
-------------------

**Session persistence** is a feature of the load balancing service that
forces multiple requests, of the same protocol, from clients to be
directed to the same node. This is common with many web applications
that do not inherently share application state between back-end servers.

There are two types (or modes) of session persistence:

+-------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Name              | Description                                                                                                                                                                                                                       |
+===================+===================================================================================================================================================================================================================================+
| ``HTTP_COOKIE``   | A session persistence mechanism that inserts an HTTP cookie and is used to determine the destination back-end node. This is supported for HTTP load balancing only.                                                               |
+-------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``SOURCE_IP``     | A session persistence mechanism that will keep track of the source IP address that is mapped and is able to determine the destination back-end node. This is supported for HTTPS pass-through and non-HTTP load balancing only.   |
+-------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

List Session Persistence Configuration
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $sessionPersistence = $loadBalancer->sessionPersistence();
    $sessionPersistenceType = $sessionPersistence->persistenceType;

    /** @var $sessionPersistenceType null | 'HTTP_COOKIE' | 'SOURCE_IP' **/

In the example above:

-  If session persistence is enabled, the value of
   ``$sessionPersistenceType`` is the type of session persistence:
   either ``HTTP_COOKIE`` or ``SOURCE_IP``.
-  If session persistence is disabled, the value of
   ``$sessionPersistenceType`` is ``null``.

Enable Session Persistence
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $sessionPersistence = $loadBalancer->sessionPersistence();
    $sessionPersistence->update(array(
        'persistenceType' => 'HTTP_COOKIE'
    ));

Disable Session Persistence
~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $sessionPersistence = $loadBalancer->sessionPersistence();
    $sessionPersistence->delete();

Connection Logging
------------------

The **connection logging** feature allows logs to be delivered to a
Cloud Files account every hour. For HTTP-based protocol traffic, these
are Apache-style access logs. For all other traffic, this is connection
and transfer logging.

Check Logging Configuration
~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    /** @var $connectionLogging bool **/

    $connectionLogging = $loadBalancer->hasConnectionLogging();

In the example above:

-  If connection logging is enabled, the value of ``$connectionLogging``
   is ``true``.
-  If connection logging is disabled, the value of
   ``$connectionLogging`` is ``false``.

Enable Connection Logging
~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $loadBalancer->enableConnectionLogging(true);

Disable Connection Logging
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $loadBalancer->enableConnectionLogging(false);

Error Page
----------

An **error page** is the html file that is shown to the end user when an
error in the service has been thrown. By default every virtual server is
provided with the default error file. It is also possible to set a
custom error page for a load balancer.

View Error Page Content
~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $errorPage = $loadBalancer->errorPage();
    $errorPageContent = $errorPage->content;

    /** @var $errorPageContent string **/

In the example above the value of ``$errorPageContent`` is the HTML for
that page. This could either be the HTML of the default error page or of
your custom error page.

Set Custom Error Page
~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $errorPage = $loadBalancer->errorPage();
    $errorPage->update(array(
        'content' => '<HTML content of custom error page>'
    ));

Delete Custom Error Page
~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $errorPage = $loadBalancer->errorPage();
    $errorPage->delete();

Allowed Domains
---------------

**Allowed domains** are a restricted set of domain names that are
allowed to add load balancer nodes.

List Allowed Domains
~~~~~~~~~~~~~~~~~~~~

You can list all allowed domains using a load balancer service object.
An instance of ``OpenCloud\Common\Collection\PaginatedIterator`` is
returned.

.. code:: php

    $allowedDomains = $loadBalancerService->allowedDomainList();
    foreach ($allowedDomains as $allowedDomain) {
        /** @var $allowedDomain OpenCloud\LoadBalancer\Resource\AllowedDomain **/
    }

Access Lists
------------

**Access Lists** allow fine-grained network access to a load balancer's
VIP. Using access lists, network traffic to a load balancer's VIP can be
allowed or denied from a single IP address, multiple IP addresses or
entire network subnets.

Note that ``ALLOW`` network items will take precedence over ``DENY``
network items in an access list.

To reject traffic from all network items except those with the ``ALLOW``
type, add a ``DENY`` network item with the address of ``0.0.0.0/0``.

View Access List
~~~~~~~~~~~~~~~~

You can view a load balancer's access list. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $accessList = $loadBalancer->accessList();
    foreach ($accessList as $networkItem) {
        /** @var $networkItem OpenCloud\LoadBalancer\Resource\Access **/
    }

Add Network Items To Access List
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can add network items to a load balancer's access list very easily:

.. code:: php

    $loadBalancer->createAccessList(array(
        (object) array(
            'type'    => 'ALLOW',
            'address' => '206.160.165.1/24'
        ),
        (object) array(
            'type'    => 'DENY',
            'address' => '0.0.0.0/0'
        )
    ));

In the above example, we allowed access for 1 IP address, and used the
"0.0.0.0" wildcard to blacklist all other traffic.

Remove Network Item From Access List
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You an remove a network item from a load balancer's access list.

.. code:: php

    $networkItem->delete();

Content Caching
---------------

When **content caching** is enabled on a load balancer,
recently-accessed files are stored on the load balancer for easy
retrieval by web clients. Requests to the load balancer for these files
are serviced by the load balancer itself, which reduces load off its
back-end nodes and improves response times as well.

Check Content Caching Configuration
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    /** @var $contentCaching bool **/

    $contentCaching = $loadBalancer->hasContentCaching();

In the example above:

-  If content caching is enabled, the value of ``$contentCaching`` is
   ``true``.
-  If content caching is disabled, the value of ``$contentCaching`` is
   ``false``.

Enable Content Caching
~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $loadBalancer->enableContentCaching(true);

Disable Content Caching
~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $loadBalancer->enableContentCaching(false);

SSL Termination
---------------

The SSL Termination feature allows a load balancer user to terminate SSL
traffic at the load balancer layer versus at the web server layer. A
user may choose to configure SSL Termination using a key and an SSL
certificate or an (Intermediate) SSL certificate.

When SSL Termination is configured on a load balancer, a secure shadow
server is created that listens only for secure traffic on a
user-specified port. This shadow server is only visible to and
manageable by the system. Existing or updated attributes on a load
balancer with SSL Termination will also apply to its shadow server. For
example, if Connection Logging is enabled on an SSL load balancer, it
will also be enabled on the shadow server and Cloud Files logs will
contain log files for both.

View current SSL termination config
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    /** @var $sslConfig OpenCloud\LoadBalancer\Resource\SSLTermination **/

    $sslConfig = $loadBalancer->SSLTermination();

Update SSL termination config
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $sslConfig->update(array(
        'enabled'     => true,
        'securePort'  => 443,
        'privateKey'  => $key,
        'certificate' => $cert
    ));

For a full list, with explanations, of required and optional attributes,
please consult the `official
documentation <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/SSLTermination-d1e2479.html>`__

Delete SSL termination config
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $sslConfig->delete();

Metadata
--------

Metadata can be associated with each load balancer and each node for the
client's personal use. It is defined using key-value pairs where the key
and value consist of alphanumeric characters. A key is unique per load
balancer.

List metadata
~~~~~~~~~~~~~

.. code:: php

    $metadataList = $loadBalancer->metadataList();

    foreach ($metadataList as $metadataItem) {
        printf("Key: %s, Value: %s", $metadataItem->key, $metadataItem->value);
    }

Please consult the `iterator
documentation <docs/userguide/Iterators.md>`__ for more information
about iterators.

Add metadata
~~~~~~~~~~~~

.. code:: php

    $metadataItem = $loadBalancer->metadata();
    $metadataItem->create(array(
        'key'   => 'foo',
        'value' => 'bar'
    ));

Modify metadata
~~~~~~~~~~~~~~~

.. code:: php

    $metadataItem = $loadBalancer->metadata('foo');
    $metadataItem->update(array(
        'value' => 'baz'
    ));

Remove metadata
~~~~~~~~~~~~~~~

.. code:: php

    $metadataItem->delete();

Monitors
--------

The load balancing service includes a health monitoring operation which
periodically checks your back-end nodes to ensure they are responding
correctly. If a node is not responding, it is removed from rotation
until the health monitor determines that the node is functional. In
addition to being performed periodically, the health check also is
performed against every node that is added to ensure that the node is
operating properly before allowing it to service traffic. Only one
health monitor is allowed to be enabled on a load balancer at a time.

.. code:: php

    /** @var $healthMonitor OpenCloud\LoadBalancer\Resource\HealthMonitor **/

    $healthMonitor = $loadBalancer->healthMonitor();

    printf(
        "Monitoring type: %s, delay: %s, timeout: %s, attempts before deactivation: %s",
        $healthMonitor->type, $healthMonitor->delay, $healthMonitor->timeout
    );

For a full list, with explanations, of required and optional attributes,
please consult the `official
documentation <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/Monitor_Connections-d1e3536.html>`__

Update or delete
~~~~~~~~~~~~~~~~

.. code:: php

    // Update
    $healthMonitor->update(array(
        'delay'   => 120,
        'timeout' => 60,
        'type'    => 'CONNECT'
        'attemptsBeforeDeactivation' => 3
    ));

    // Delete
    $healthMonitor->delete();

Statistics
----------

You can retrieve detailed stats about your load balancer, including the
following information:

-  ``connectTimeOut`` – Connections closed by this load balancer because
   the 'connect\_timeout' interval was exceeded.
-  ``connectError`` – Number of transaction or protocol errors in this
   load balancer.
-  ``connectFailure`` – Number of connection failures in this load
   balancer.
-  ``dataTimedOut`` – Connections closed by this load balancer because
   the 'timeout' interval was exceeded.
-  ``keepAliveTimedOut`` – Connections closed by this load balancer
   because the 'keepalive\_timeout' interval was exceeded.
-  ``maxConn`` – Maximum number of simultaneous TCP connections this
   load balancer has processed at any one time.

.. code:: php

    /** @var $stats OpenCloud\LoadBalancer\Resource\Stats **/

    $stats = $loadBalancer->stats();

Usage Reports
-------------

The load balancer usage reports provide a view of all transfer activity,
average number of connections, and number of virtual IPs associated with
the load balancing service. Current usage represents all usage recorded
within the preceding 24 hours. Values for both incomingTransfer and
outgoingTransfer are expressed in bytes transferred.

The optional startTime and endTime parameters can be used to filter all
usage. If the startTime parameter is supplied but the endTime parameter
is not, then all usage beginning with the startTime will be provided.
Likewise, if the endTime parameter is supplied but the startTime
parameter is not, then all usage will be returned up to the endTime
specified.

.. code:: php

    # View billable LBs
    $billable = $service->billableLoadBalancerList();

    foreach ($billable as $loadBalancer) {
       /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/

       # View usage
       /** @var $usage OpenCloud\LoadBalancer\Resource\UsageRecord **/
       $usage = $loadBalancer->usage();

       echo $usage->averageNumConnections, PHP_EOL;
    }

