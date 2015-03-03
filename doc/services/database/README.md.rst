Databases
=========

A **cloud database** is a MySQL relational database service that allows
customers to programatically provision database instances of varying
virtual resource sizes without the need to maintain and/or update MySQL.

Getting started
---------------

1. Instantiate a Rackspace client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    use OpenCloud\Rackspace;
    use OpenCloud\Common\Constants\State;

    $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
    ));

2. Create a database server instance.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $databaseService = $client->databaseService('cloudDatabases', 'DFW');

    $twoGbFlavor = $databaseService->flavor(3);

    $dbInstance = $databaseService->instance();
    $dbInstance->name = 'Demo database instance';
    $dbInstance->volume = new stdClass();
    $dbInstance->volume->size = 20; // GB
    $dbInstance->flavor = $twoGbFlavor;
    $dbInstance->create();

    $dbInstance->waitFor(State::ACTIVE, null, function ($dbInstance) {

        printf("Database instance build status: %s\n", $dbInstance->status);

    });

The example above creates a database server instance with 20GB of disk
space and 2GB of memory, then waits for it to become ACTIVE.

3. Create a database on the database server instance.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $db = $dbInstance->database();
    $db->name = 'demo_db';

    $db->create();

The example above creates a database named ``demo_db`` on the database
server instance created in the previous step.

4. Create database user and give it access to database.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $user = $dbInstance->user();
    $user->name = 'demo_user';
    $user->password = 'h@X0r!';
    $user->databases = array('demo_db');

    $user->create();

The example above creates a database user named ``demo_user``, sets its
password and gives it access to the ``demo_db`` database created in the
previous step.

5. Optional step: Create a load balancer to allow access to the database from the Internet.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The database created in the previous step can only be accessed from the
Rackspace private network (aka ``SERVICENET``). If you have a cloud
server instance in the same region as the database server instance, you
will be able to connect to the database from that cloud server instance.

If, however, you would like to access the database from the Internet,
you will need to create a load balancer with an IP address that is
routable from the Internet and attach the database server instance as a
back-end node of this load balancer.

.. code:: php

    $loadBalancerService = $client->loadBalancerService('cloudLoadBalancers', 'DFW');

    $loadBalancer = $loadBalancerService->loadBalancer();

    $loadBalancer->name = 'Load balancer - DB';
    $loadBalancer->addNode($dbInstance->hostname, 3306);
    $loadBalancer->port = 3306;
    $loadBalancer->protocol = 'MYSQL';
    $loadBalancer->addVirtualIp('PUBLIC');

    $loadBalancer->create();

    $loadBalancer->waitFor(State::ACTIVE, null, function ($lb) {
        printf("Load balancer build status: %s\n", $lb->status);
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
