Databases
=========

Setup
-----

In order to interact with the functionality of databases, you must first
retrieve the details of the instance itself. To do this, you must substitute
`{instanceId}` for your instance's ID:

.. code-block:: php

  $instance = $service->instance('{instanceId}');


Creating a new database
-----------------------

To create a new database, you must supply it with a name; you can
optionally specify its character set and collating sequence:

.. code-block:: php

  // Create an empty object
  $database = $instance->database();

  // Send to API
  $database->create(array(
      'name'          => 'production',
      'character_set' => 'utf8',
      'collate'       => 'utf8_general_ci'
  ));

You can find values for ``character_set`` and ``collate`` at `the MySQL
website <http://dev.mysql.com/doc/refman/5.0/en/charset-mysql.html>`__.


Deleting a database
-------------------

.. code-block:: php

  $database->delete();

.. note::

   This is a destructive operation: all your data will be wiped away and will
   not be retrievable.


Listing databases
-----------------

.. code-block:: php

  $databases = $service->databaseList();

  foreach ($databases as $database) {
      /** @param $database OpenCloud\Database\Resource\Database */
  }
