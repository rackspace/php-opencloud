Records
=======

Setup
-----

In order to interact with the functionality of records, you must first
retrieve the details of the domain itself. To do this, you must substitute
`{domainId}` for your domain's ID:

.. code-block:: php

  $domain = $service->domain('{domainId}');


Get record
----------

In order to retrieve details for a specific DNS record, you will need
its **id**:

.. code:: php

  $record = $domain->record('{recordId}');

If you do not have this ID at your disposal, you can traverse the record
collection and do a string comparison (detailed below).


List records
------------

This call lists all records configured for the specified domain.

.. code:: php

  $records = $domain->recordList();

  foreach ($records as $record) {
      printf("Record name: %s, ID: %s, TTL: %s\n", $record->name, $record->id, $record->ttl);
  }


Query parameters
~~~~~~~~~~~~~~~~

You can pass in an array of query parameters for greater control over
your search:

+------------+--------------+------------------------+
| Name       | Data type    | Description            |
+============+==============+========================+
| ``type``   | ``string``   | The record type        |
+------------+--------------+------------------------+
| ``name``   | ``string``   | The record name        |
+------------+--------------+------------------------+
| ``data``   | ``string``   | Data for this record   |
+------------+--------------+------------------------+


Find a record ID from its name
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For example:

.. code:: php

  $records = $domain->recordList(array(
      'name' => 'imap.example.com',
      'type' => 'MX'
  ));

  foreach ($records as $record) {
      $recordId = $record->id;
  }


Add record
----------

This call adds a new record to the specified domain:

.. code:: php

  $record = $domain->record(array(
      'type' => 'A',
      'name' => 'example.com',
      'data' => '192.0.2.17',
      'ttl'  => 3600
  ));

  $record->create();


Please be aware that records that are added with a different hostname
than the parent domain might fail silently.

Modify record
-------------

.. code:: php

  $record = $domain->record('{recordId}');
  $record->ttl -= 100;
  $record->update();


Delete record
-------------

.. code:: php

  $record->delete();
