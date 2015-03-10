Users
=====


Object properties/methods
-------------------------

+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| Property        | Description                                                                                                                                                                                                                                                                                                                   | Getter                                     | Setter                                                                                                        |
+=================+===============================================================================================================================================================================================================================================================================================================================+============================================+===============================================================================================================+
| id              | The unique ID for this user                                                                                                                                                                                                                                                                                                   | ``getId()``                                | ``setId()``                                                                                                   |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| username        | Username for this user                                                                                                                                                                                                                                                                                                        | ``getUsername()``                          | ``setUsername()``                                                                                             |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| email           | User's email address                                                                                                                                                                                                                                                                                                          | ``getEmail()``                             | ``setEmail()``                                                                                                |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| enabled         | Whether or not this user can consume API functionality                                                                                                                                                                                                                                                                        | ``getEnabled()`` or ``isEnabled()``        | ``setEnabled()``                                                                                              |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| password        | Either a user-defined string, or an automatically generated one, that provides security when authenticating.                                                                                                                                                                                                                  | ``getPassword()`` only valid on creation   | ``setPassword()`` to set local property only. To set password on API (retention), use ``updatePassword()``.   |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| defaultRegion   | Default region associates a user with a specific regional datacenter. If a default region has been assigned for this user and that user has **NOT** explicitly specified a region when creating a service object, the user will obtain the service from the default region.                                                   | ``getDefaultRegion()``                     | ``setDefaultRegion()``                                                                                        |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+
| domainId        | Domain ID associates a user with a specific domain which was assigned when the user was created or updated. A domain establishes an administrative boundary for a customer and a container for a customer's tenants (accounts) and users. Generally, a domainId is the same as the primary tenant id of your cloud account.   | ``getDomainId()``                          | ``setDomainId()``                                                                                             |
+-----------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+--------------------------------------------+---------------------------------------------------------------------------------------------------------------+

List users
----------

.. code-block:: php

  $users = $service->getUsers();

  foreach ($users as $user) {
     // ...
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/list_users.php>`_


Retrieve a user by username
---------------------------

.. code-block:: php

  $user = $service->getUser('jamie');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/get_user_by_name.php>`_


Retrieve a user by user ID
--------------------------

.. code-block:: php

  use OpenCloud\Identity\Constants\User as UserConst;

  $user = $service->getUser('{userId}', UserConst::MODE_ID);

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/get_user_by_id.php>`_


Retrieve a user by email address
--------------------------------

.. code-block:: php

  use OpenCloud\Identity\Constants\User as UserConst;

  $user = $service->getUser('{emailAddress}', UserConst::MODE_EMAIL);

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/get_user_by_email.php>`_


Create user
-----------

There are a few things to bear in mind when creating a user:

*  This operation is available only to users who hold the
   ``identity:user-admin`` role. This admin can create a user who holds
   the ``identity:default`` user role.

*  The created user **will** have access to APIs but **will not** have
   access to the Cloud Control Panel.

*  A maximum of 100 account users can be added per account.

*  If you attempt to add a user who already exists, an HTTP error 409
   results.


The ``username`` and ``email`` properties are required for creating a
user. Providing a ``password`` is optional; if omitted, one will be
automatically generated and provided in the response.


.. code-block:: php

  use Guzzle\Http\Exception\ClientErrorResponseException;

   $user = $service->createUser(array(
      'username' => 'newUser',
      'email'    => 'foo@bar.com'
   ));

  // show generated password
  echo $user->getPassword();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/add_user.php>`_


Update user
-----------

When updating a user, specify which attribute/property you want to
update:

.. code-block:: php

  $user->update(array(
     'email' => 'new_email@bar.com'
  ));


Updating a user password
------------------------

Updating a user password requires calling a distinct method:

.. code-block:: php

  $user->updatePassword('password123');


Delete user
-----------

.. code-block:: php

  $user->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/delete_user.php>`_


List credentials
----------------

This operation allows you to see your non-password credential types for
all authentication methods available.

.. code-block:: php

  $creds = $user->getOtherCredentials();


Get user API key
----------------

.. code-block:: php

  echo $user->getApiKey();


Reset user API key
------------------

When resetting an API key, a new one will be automatically generated for
you:

.. code-block:: php

  $user->resetApiKey();
  echo $user->getApiKey();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Identity/reset_api_key.php>`_
