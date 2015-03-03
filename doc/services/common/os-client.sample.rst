.. code-block:: php

  <?php

  require 'vendor/autoload.php';

  use OpenCloud\OpenStack;

  $client = new OpenStack('{keystoneUrl}', array(
    'username' => '{username}',
    'password' => '{apiKey}',
    'tenantId' => '{tenantId}',
  ));
