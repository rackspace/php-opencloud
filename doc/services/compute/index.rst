Compute v2
==========

.. include:: ../common/clients.sample.rst

Compute service
~~~~~~~~~~~~~~~

Now to instantiate the Compute service:

.. code-block:: php

  $service = $client->computeService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  images
  flavors
  servers
  keypairs


Glossary
--------

.. glossary::

  image
    An image is a collection of files for a specific operating system that you
    use to create or rebuild a server. Rackspace provides prebuilt images. You
    can also create custom images from servers that you have launched.

  flavor
    A flavor is a named definition of certain server parameters such as
    the amount of RAM and disk space available. (There are other parameters
    set via the flavor, such as the amount of disk space and the number of
    virtual CPUs, but a discussion of those is too in-depth for a simple
    Getting Started Guide like this one.)

  server
    A server is a virtual machine instance in the Cloud Servers environment.


Further Links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/servers/api/v2/cs-gettingstarted/content/overview.html>`_
- `API Developer Guide <http://docs.rackspace.com/servers/api/v2/cs-devguide/content/ch_preface.html>`_
- `API release history <http://docs.rackspace.com/servers/api/v2/cs-releasenotes/content/ch_preface.html>`_
