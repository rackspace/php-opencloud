Setup
-----

Rackspace setup
~~~~~~~~~~~~~~~

.. include:: /services/common/rs-client.rst


OpenStack setup
~~~~~~~~~~~~~~~

If you're an OpenStack user, you will also need to prove a few other
configuration parameters:

.. code-block:: php

  $client = new OpenCloud\OpenStack('{keystoneUrl}', array(
    'username' => '{username}',
    'password' => '{apiKey}',
    'tenantId' => '{tenantId}',
  ));
