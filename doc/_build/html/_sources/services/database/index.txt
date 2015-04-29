Databases v1
============

.. include:: ../common/clients.sample.rst

Databases service
~~~~~~~~~~~~~~~~~

Now to instantiate the Databases service:

.. code-block:: php

  $service = $client->databaseService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  instances
  databases
  users
  datastores


Glossary
--------

.. glossary::

  configuration group
    A configuration group is a collection of key/value pairs which configure a
    database instance. Some directives are capable of being applied dynamically,
    while other directives require a server restart to take effect. The
    configuration group can be applied to an instance at creation or applied to
    an existing instance to modify the behavior of the running datastore on the
    instance.

  flavor
    A flavor is an available hardware configuration for a database instance.
    Each flavor has a unique combination of memory capacity and priority for
    CPU time.

  instance
    A database instance is an isolated MySQL instance in a single tenant
    environment on a shared physical host machine. Also referred to as
    instance.

  database
    A database is a local MySQL database running on an instance.

  user
    A user is a local MySQL user that can access a database running on an
    instance.

  datastore
    The database engine running on your instance. Currently, there is support
    for MySQL 5.6, MySQL 5.1, Percona 5.6 and MariaDB 10.

  volume
    A volume is user-specified storage that contains the database engine data
    directory. Volumes are automatically provisioned on shared Internet Small
    Computer System Interface (iSCSI) storage area networks (SAN) that provide
    for increased performance, scalability, availability and manageability.
    Applications with high I/O demands are performance optimized and data is
    protected through both local and network RAID-10.


Further Links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/cdb/api/v1.0/cdb-getting-started/content/DB_Overview.html>`_
- `API Developer Guide <http://docs.rackspace.com/cdb/api/v1.0/cdb-devguide/content/overview.html>`_
- `API release history <http://docs.rackspace.com/cdb/api/v1.0/cdb-getting-started/content/DB_Doc_Change_History.html>`_
