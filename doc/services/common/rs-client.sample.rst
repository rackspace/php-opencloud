The first thing to do is pass in your credentials and instantiate a Rackspace
client:

.. code-block:: php

  <?php

  require 'vendor/autoload.php';

  use OpenCloud\Rackspace;

  $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
    'username' => '{username}',
    'apiKey'   => '{apiKey}',
  ));
