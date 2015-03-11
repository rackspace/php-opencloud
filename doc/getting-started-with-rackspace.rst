Getting Started with Rackspace
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

In this example, you will write code that will create a Cloud Servers instance
running Ubuntu.


1. Setup the client and pass in your credentials
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To authenticate against the Rackspace API and use its services:

.. code-block:: php

  <?php

  require 'vendor/autoload.php';

  use OpenCloud\Rackspace;

  $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
      'username' => 'foo',
      'apiKey'   => 'bar'
  ));

You can see in the first example that the constant
``Rackspace::US_IDENTITY_ENDPOINT`` is just a string representation of
Rackspace's identity endpoint
(``https://identity.api.rackspacecloud.com/v2.0/``). Another difference
is that Rackspace uses API key for authentication, whereas OpenStack
uses a generic password.


2. Pick what service you want to use
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

In this case, we want to use the Compute (Nova) service:

.. code-block:: php

  $compute = $client->computeService(null, 'ORD');

The first argument is the **name** of the service as it appears in the
OpenStack service catalog. If in doubt, you can leave blank and it will
revert to the default name for the service. The second argument is the
region; you may use:

-  **DFW** (Dallas)
-  **ORD** (Chicago)
-  **IAD** (Virginia)
-  **LON** (London)
-  **HKG** (Hong Kong)
-  **SYD** (Sydney)

The third and last argument is the type of URL; you may use either
``publicURL`` or ``internalURL``. If you select ``internalUrl`` all API
traffic will use ServiceNet (internal IPs) and will receive a
performance boost.

3. Select your server image
~~~~~~~~~~~~~~~~~~~~~~~~~~~

Servers are based on "images", which are effectively just the type of
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
which offer a much higher spec. The 'flavor' of a server is its hardware
configuration. So if you want a 2GB instance but don't know the ID, you
have to traverse the list:

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

You can also call a polling function that checks on the build process:

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

Next steps
----------

Read our docs for the `Compute v2 <services/compute>`_ service.
