Keypairs
========

Generate a new keypair
----------------------

This operation creates a new keypair under a provided name; the public key
value is automatically generated for you.

.. code-block:: php

  // Instantiate empty object
  $keypair = $service->keypair();

  // Send to API
  $keypair->create(array(
     'name' => 'jamie_keypair_1'
  ));

  // Save these!
  $pubKey = $keypair->getPublicKey();
  $priKey = $keypair->getPrivateKey();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Compute/create_new_keypair.php>`_


Upload existing keypair
-----------------------

This operation creates a new keypair according to a provided name and public
key value. This is useful when the public key already exists on your local
filesystem.

.. code-block:: php

  $keypair = $service->keypair();

  // $key needs to be the string content of the key file, not the filename
  $content = file_get_contents('~/.ssh/id_rsa.pub');

  $keypair->create(array(
     'name'      => 'main_key',
     'publicKey' => $content
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Compute/upload_existing_keypair.php>`_


List keypairs
-------------

To list all existing keypairs:

.. code-block:: php

  $keys = $service->listKeypairs();

  foreach ($keys as $key) {

  }


Delete keypairs
---------------

To delete a specific keypair:

.. code-block:: php

  $keypair->delete();
