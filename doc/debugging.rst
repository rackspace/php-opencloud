Debugging
=========

There are two important debugging strategies to use when encountering
problems with HTTP transactions.

Strategy 1: Meaningful exception handling
-----------------------------------------

If the API returns a ``4xx`` or ``5xx`` status code, it indicates that
there was an error with the sent request, meaning that the transaction
cannot be adequately completed.

The Guzzle HTTP component, which forms the basis of our SDK's transport
layer, utilizes `numerous exception
classes <https://github.com/guzzle/guzzle/tree/master/src/Guzzle/Http/Exception>`__
to handle this error logic.

The two most common exception classes are:

-  ``Guzzle\Http\Exception\ClientErrorResponseException``, which is
   thrown when a ``4xx`` response occurs

-  ``Guzzle\Http\Exception\ServerErrorResponseException``, which is
   thrown when a ``5xx`` response occurs

Both of these classes extend the base ``BadResponseException`` class.

This provides you with the granularity you need to debug and handle
exceptions.

An example with Swift
~~~~~~~~~~~~~~~~~~~~~

If you're trying to retrieve a Swift resource, such as a Data Object,
and you're not completely certain that it exists, it makes sense to wrap
your call in a try/catch block:

.. code-block:: php

  use Guzzle\Http\Exception\ClientErrorResponseException;

  try {
      return $service->getObject('foo.jpg');
  } catch (ClientErrorResponseException $e) {
      if ($e->getResponse()->getStatusCode() == 404) {
        // Okay, the resource does not exist
        return false;
      }
  } catch (\Exception $e) {
      // Some other exception was thrown...
  }


Both ``ClientErrorResponseException`` and
``ServerErrorResponseException`` have two methods that allow you to
access the HTTP transaction:

.. code-block:: php

  // Find out the faulty request
  $request = $e->getRequest();

  // Display everything by casting as string
  echo (string) $request;

  // Find out the HTTP response
  $response = $e->getResponse();

  // Output that too
  echo (string) $response;


Strategy 2: Wire logging
------------------------

Guzzle provides a `Log
plugin <http://docs.guzzlephp.org/en/latest/plugins/log-plugin.html>`__
that allows you to log everything over the wire, which is useful if you
don't know what's going on.

Here's how you enable it:

Install the plugin
~~~~~~~~~~~~~~~~~~

.. code-block:: bash

  composer require guzzle/guzzle


Add to your client
~~~~~~~~~~~~~~~~~~

.. code-block:: php

  use Guzzle\Plugin\Log\LogPlugin;

  $client->addSubscriber(LogPlugin::getDebugPlugin());


The above will add a generic logging subscriber to your client, which
will output every HTTP transaction to `STDOUT`.
