Statistics and Usage Reports
============================

.. include:: lb-setup.sample.rst

Retrieve LB stats
-----------------

You can retrieve detailed stats about your load balancer, including the
following information:

-  ``connectTimeOut`` – Connections closed by this load balancer because
   the 'connect_timeout' interval was exceeded.
-  ``connectError`` – Number of transaction or protocol errors in this
   load balancer.
-  ``connectFailure`` – Number of connection failures in this load balancer.
-  ``dataTimedOut`` – Connections closed by this load balancer because
   the 'timeout' interval was exceeded.
-  ``keepAliveTimedOut`` – Connections closed by this load balancer
   because the 'keepalive_timeout' interval was exceeded.
-  ``maxConn`` – Maximum number of simultaneous TCP connections this
   load balancer has processed at any one time.

.. code-block:: php

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

.. code-block:: php

  # View billable LBs
  $billable = $service->billableLoadBalancerList();

  foreach ($billable as $loadBalancer) {
     /** @var $loadBalancer OpenCloud\LoadBalancer\Resource\LoadBalancer **/

     # View usage
     /** @var $usage OpenCloud\LoadBalancer\Resource\UsageRecord **/
     $usage = $loadBalancer->usage();

     echo $usage->averageNumConnections, PHP_EOL;
  }
