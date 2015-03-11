Logging
=======

Logger injection
----------------

As the ``Rackspace`` client extends the ``OpenStack`` client, they both support
passing ``$options`` as an array via the constructor's third parameter. The
options are passed as a config to the `Guzzle` client, but also allow to inject
your own logger.

Your logger should implement the ``Psr\Log\LoggerInterface`` `as defined in
PSR-3 <https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md>`_.
One example of a compatible logger is `Monolog <https://github.com/Seldaek/monolog>`_.
When the client does create a service, it will inject the logger if one is
available.

To inject a ``LoggerInterface`` compatible logger into a new client:

.. code-block:: php

  use Monolog\Logger;
  use OpenCloud\OpenStack;

  // create a log channel
  $logger = new Logger('name');

  $client = new OpenStack('http://identity.my-openstack.com/v2.0', array(
    'username' => 'foo',
    'password' => 'bar'
  ), array(
    'logger' => $logger,
  ));
