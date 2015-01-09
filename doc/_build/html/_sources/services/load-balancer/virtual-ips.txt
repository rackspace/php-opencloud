Virtual IPs
===========

.. include:: lb-setup.sample.rst


List Virtual IPs
----------------

You can list the VIPs associated with a load balancer like so:

.. code-block:: php

  $vips = $loadBalancer->virtualIpList();

  foreach ($vips as $vip) {
      /** @var $vip of OpenCloud\LoadBalancer\Resource\VirtualIp **/
  }


Get existing VIP
----------------

To retrieve the details of an existing VIP on a load balancer, you will need
its ID:

.. code-block::

  $vip = $loadBalancer->virtualIp('{virtualIpId}');


Add Virtual IPv6
----------------

You can add additional IPv6 VIPs to a load balancer using the following method:

.. code-block:: php

  use OpenCloud\LoadBalancer\Enum\IpType;

  $loadBalancer->addVirtualIp(IpType::PUBLIC, 6);

the first argument is the type of network your IP address will server traffic in
- and can either be ``PUBLIC`` or ``PRIVATE``. The second argument is the version
of IP address, either ``4`` or ``6``.


Add Virtual IPv4
----------------

Similar to above:

.. code-block:: php

  use OpenCloud\LoadBalancer\Enum\IpType;

  $loadBalancer->addVirtualIp(IpType::PUBLIC, 4);


Remove Virtual IP
-----------------

You can remove a VIP from a load balancer.

.. code-block:: php

  $vip->remove();


.. note::

  A load balancer must have at least one VIP associated with it. If you try to
  remove a load balancer's last VIP, a ``ClientErrorResponseException`` will be
  thrown.
