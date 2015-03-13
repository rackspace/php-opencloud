Allowed Domains
===============

List Allowed Domains
--------------------

You can list all allowed domains using a load balancer service object.
An instance of ``OpenCloud\Common\Collection\PaginatedIterator`` is
returned.

.. code-block:: php

  $allowedDomains = $service->allowedDomainList();

  foreach ($allowedDomains as $allowedDomain) {
      /** @var $allowedDomain OpenCloud\LoadBalancer\Resource\AllowedDomain **/
  }


Access Lists
============

Access Lists allow fine-grained network access to a load balancer's VIP. Using
access lists, network traffic to a load balancer's VIP can be allowed or denied
from a single IP address, multiple IP addresses or entire network subnets.

Note that ``ALLOW`` network items will take precedence over ``DENY`` network
items in an access list.

To reject traffic from all network items except those with the ``ALLOW``
type, add a ``DENY`` network item with the address of ``0.0.0.0/0``.

.. include:: lb-setup.sample.rst


View Access List
----------------

You can view a load balancer's access list:

.. code-block:: php

  $accessList = $loadBalancer->accessList();

  foreach ($accessList as $networkItem) {
      /** @var $networkItem OpenCloud\LoadBalancer\Resource\Access **/
  }


Add Network Items To Access List
--------------------------------

You can add network items to a load balancer's access list very easily:

.. code-block:: php

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

Get the executable PHP scripts for this example:

* `Blacklist IP range <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/LoadBalancer/blacklist-ip-range.php>`_
* `Limit access to 1 IP <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/LoadBalancer/limit-access-to-1-ip.php>`_


Remove Network Item From Access List
------------------------------------

You an remove a network item from a load balancer's access list:

.. code-block:: php

  $networkItem->delete();
