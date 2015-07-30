Users
=====

Setup
-----

Finally, in order to interact with the functionality of databases, you must
first retrieve the details of the instance itself. To do this, you must
substitute `{instanceId}` for your instance's ID:

.. code-block:: php

  $instance = $service->instance('{instanceId}');


Creating users
--------------

Database users exist at the ``Instance`` level, but can be associated
with a specific ``Database``. They are represented by the
``OpenCloud\Database\Resource\User`` class.

.. code-block:: php

  // New instance of OpenCloud\Database\Resource\User
  $user = $instance->user();

  // Send to API
  $user->create(array(
      'name'      => 'Alice',
      'password'  => 'fooBar'
      'databases' => array('production')
  ));


Deleting a user
---------------

.. code-block:: php

  $user->delete();


The root user
-------------

By default, Cloud Databases does not enable the root user. In most
cases, the root user is not needed, and having one can leave you open to
security violations. However, if you do want to enable access to the root user:

.. code-block:: php

    $rootUser = $instance->enableRootUser();


This returns a regular ``User`` object with the ``name`` attribute set
to ``root`` and the ``password`` attribute set to an auto-generated
password.


Check if root user is enabled
-----------------------------

.. code-block:: php

  // true for yes, false for no
  $instance->isRootEnabled();


Grant database access
---------------------

To grant access to one or more databases, you can run:

.. code-block:: php

    $user = $instance->user('{userName}');
    $user->grantDbAccess(['{dbName1}', '{dbName2}']);

