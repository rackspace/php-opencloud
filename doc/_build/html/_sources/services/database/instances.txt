Instances
=========

Create a new instance
---------------------

.. code-block:: php

  // Create an empty object
  $instance = $service->instance();

  // Send to the API
  $instance->create(array(
      'name'   => '{name}',
      'flavor' => $service->flavor('{flavorId}'),
      'volume' => array('size' => 4)               // 4GB of volume disk
  ));

`Get the executable PHP script for this sample <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/create-instance.php>`__


Waiting for the instance to build
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The SDK provides a blocking operation that will wait until your instance resource
has transitioned into an ``ACTIVE`` state. During this period, it will
continuously poll the API and break the loop when the state has been achieved:

.. code-block:: php

  $instance->waitFor('ACTIVE', null, function ($instance) {
      // This will be executed continuously
      printf("Database instance build status: %s\n", $instance->status);
  });


Connecting an instance to a load balancer
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The instance created in the previous step can only be accessed from the
Rackspace private network (aka ``SERVICENET``). If you have a cloud
server instance in the same region as the database server instance, you
will be able to connect to the database from that cloud server instance.

If, however, you would like to access the database from the Internet,
you will need to create a load balancer with an IP address that is
routable from the Internet and attach the database server instance as a
back-end node of this load balancer.

.. code-block:: php

  $lbService = $client->loadBalancerService(null, '{region}');

  // Create empty object
  $loadBalancer = $lbService->loadBalancer();

  // Associate this LB with the instance as a "node"
  $loadBalancer->addNode($instance->hostname, 3306);
  $loadBalancer->addVirtualIp('PUBLIC');

  // Configure other parameters and send to the API
  $loadBalancer->create(array(
    'name'     => 'DB Load Balancer',
    'port'     => 3306,
    'protocol' => 'MYSQL',
  ));

  // Wait for the resource to create
  $loadBalancer->waitFor('ACTIVE', null, function ($loadBalancer) {
      printf("Load balancer build status: %s\n", $loadBalancer->status);
  });

  foreach ($loadBalancer->virtualIps as $vip) {
      if ($vip->type == 'PUBLIC') {
          printf("Load balancer public %s address: %s\n", $vip->ipVersion, $vip->address);
      }
  }

In the example above, a load balancer is created with the database
server instance as its only back-end node. Further, this load balancer
is configured to listen for MySQL connections on port 3306. Finally a
virtual IP address (VIP) is configured in the ``PUBLIC`` network address
space so that this load balancer may receive connections from the
Internet.

Once the load balancer is created and becomes ``ACTIVE``, it's
Internet-accessible IP addresses are printed out. If you connect to any
of these IP addresses on port 3306 using the MySQL protocol, you will be
connected to the database created in step 3.


Retrieving an instance
----------------------

.. code-block:: php

    $instance = $service->instance('{instanceId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Database/get-instance.php>`__


Updating an instance
--------------------

An instance can be updated to use a specific `configuration <configurations>`__ as shown below.

.. code-block:: php

  $instance->update(array(
      'configuration' => '{configurationId}'
  ));

.. note::

  If any parameters in the associated configuration require a restart, then you
  will need to `restart the instance <#restarting-an-instance>`__ after the update.


Deleting an instance
--------------------

.. code-block:: php

  $instance->delete();


Restarting an instance
----------------------

.. code-block:: php

  $instance->restart();


Resizing an instance's RAM
--------------------------

To change the amount of RAM allocated to the instance:

.. code-block:: php

  $flavor = $service->flavor('{flavorId}');
  $instance->resize($flavor);


Resizing an instance's volume
-----------------------------

You can also independently change the volume size to increase the disk
space:

.. code-block:: php

  // Increase to 8GB disk
  $instance->resizeVolume(8);
