The first step is to pass in your credentials and set up a client. For
Rackspace users, you will need your username and API key:

.. code-block:: php

  use OpenCloud\Rackspace;

  $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
  ));
