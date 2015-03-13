Object Store v1
===============

.. include:: ../common/clients.sample.rst

Object Store service
~~~~~~~~~~~~~~~~~~~~

Now to instantiate the Object Store service:

.. code-block:: php

  $service = $client->objectStoreService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  account
  containers
  objects
  cdn
  migrating-containers
  access

Glossary
--------

.. glossary::

  account
    The portion of the system designated for your use. An Object Store system is
    typically designed to be used by many different customers, and your user
    account is your portion of it.

  container
    A storage compartment that provides a way for you to organize data. A
    container is similar to a folder in Windows or a directory in UNIX. The
    primary difference between a container and these other file system concepts
    is that containers cannot be nested.

  cdn
    A system of distributed servers (network) that delivers web pages and other
    web content to a user based on the geographic locations of the user, the
    origin of the web page, and a content delivery server.

  metadata
    Optional information that you can assign to Cloud Files accounts,
    containers, and objects through the use of a metadata header.

  object
    An object (sometimes referred to as a file) is the unit of storage in an
    Object Store. An object is a combination of content (data) and metadata.


Further links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/files/api/v1/cf-getting-started/content/Overview-d1e01.html>`_
- `API Developer Guide <http://docs.rackspace.com/files/api/v1/cf-devguide/content/Overview-d1e70.html>`_
- `API release history <http://docs.rackspace.com/files/api/v1/cf-getting-started/content/Doc_Change_History.html>`_
