Getting Started with OpenStack
==============================

Installing the SDK
------------------

You must install through Composer, because this library has a few
dependencies:

.. code-block:: bash

  composer require rackspace/php-opencloud

Once you have installed the library, you will need to load Composer's
autoloader (which registers all the required namespaces):

.. code-block:: php

  require 'vendor/autoload.php';

And you're good to go!


Quick deep-dive: building some Nova instances
---------------------------------------------

In this example, you will write code that will create a Nova instance
running Ubuntu.


1. Setup the client and pass in your credentials
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To authenticate against Keystone:

.. code-block:: php

  use OpenCloud\OpenStack;

  $client = new OpenStack('http://my-openstack.com:35357/v2.0/', array(
      'username'   => 'foo',
      'password'   => 'bar',
      'tenantName' => 'baz'
  ));

You will need to substitute in the public URL endpoint for your Keystone
service, as well as your ``username``, ``password`` and ``tenantName``.
You can also specify your ``tenantId`` instead of ``tenantName`` if you
prefer.


2. Pick what service you want to use
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In this case, we want to use the Nova service:

.. code-block:: php

  $compute = $client->computeService('nova', 'regionOne');


The first argument is the **name** of the service as it appears in the
OpenStack service catalog. For OpenStack users, this must be retrieved
and entered in your code. If you are unsure how to retrieve the service
name, follow these steps:

1. Setup the ``$client`` object, as above
2. Copy and run this code:

.. code-block:: php

  $client->authenticate();
  print_r($client->getCatalog()->getItems());


3. This will output all the items in your service catalog. Go through
   the outputted list and find your service, making note of the "name"
   field. This is the name you will need to enter as the first argument.
   You will also be able to see the available regions.

The second argument is the region. The third and last argument is the
type of URL; you may use either ``publicURL`` or ``internalURL``.


3. Select your server image
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Instances are based on "images", which are effectively just the type of
operating system you want. Let's go through the list and find an Ubuntu
one:

.. code-block:: php

  $images = $compute->imageList();

  foreach ($images as $image) {
      if (strpos($image->name, 'Ubuntu') !== false) {
          $ubuntu = $image;
          break;
      }
  }

Alternatively, if you already know the image ID, you can do this much
easier:

.. code-block:: php

  $ubuntu = $compute->image('868a0966-0553-42fe-b8b3-5cadc0e0b3c5');


4. Select your flavor
---------------------

There are different server specs - some which offer 1GB RAM, others
which offer a much higher spec. The 'flavor' of an instance is its
hardware configuration. So if you want a 2GB instance but don't know the
ID, you have to traverse the list:

.. code-block:: php

  $flavors = $compute->flavorList();

  foreach ($flavors as $flavor) {
      if (strpos($flavor->name, '2GB') !== false) {
          $twoGbFlavor = $flavor;
          break;
      }
  }

Again, it's much easier if you know the ID:

.. code-block:: php

  $twoGbFlavor = $compute->flavor('4');


5. Thunderbirds are go!
-----------------------

Okay, you're ready to spin up a server:

.. code-block:: php

  use Guzzle\Http\Exception\BadResponseException;

  $server = $compute->server();

  try {
      $response = $server->create(array(
          'name'   => 'My lovely server',
          'image'  => $ubuntu,
          'flavor' => $twoGbFlavor
      ));
  } catch (BadResponseException $e) {
      // No! Something failed. Let's find out:
      printf("Request: %s\n\nResponse: %s", $e->getRequest(), $e->getResponse());
  }

As you can see, you're creating a server called "My lovely server" -
this will take a few minutes for the build to complete. You can always
check the progress by logging into your Controller node and running:

.. code-block:: bash

  nova list

You can also execute a polling function immediately after the ``create``
method that checks the build process:

.. code-block:: php

  use OpenCloud\Compute\Constants\ServerState;

  $callback = function($server) {
      if (!empty($server->error)) {
          var_dump($server->error);
          exit;
      } else {
          echo sprintf(
              "Waiting on %s/%-12s %4s%%",
              $server->name(),
              $server->status(),
              isset($server->progress) ? $server->progress : 0
          );
      }
  };

  $server->waitFor(ServerState::ACTIVE, 600, $callback);

So, the server will be polled until it is in an ``ACTIVE`` state, with a
timeout of 600 seconds. When the poll happens, the callback function is
executed - which in this case just logs some output.

More fun with Nova
------------------

Once you've booted up your instance, you can use other API operations to
monitor your Compute nodes. To list every node on record, you can
execute:

.. code-block:: php

  $servers = $compute->serverList();

  foreach ($servers as $server) {
      // do something with each server...
      echo $server->name, PHP_EOL;
  }

or, if you know a particular instance ID you can retrieve its details:

.. code-block:: php

  $server = $compute->server('xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxx');

allowing you to update its properties:

.. code-block:: php

  $server->update(array(
     'name' => 'New server name'
  ));

or delete it entirely:

.. code-block:: php

  $server->delete();

Next steps
----------

Read our docs for the `Compute v2 <services/compute>`_ service.
