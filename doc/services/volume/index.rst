Volumes v1
==========

.. include:: ../common/clients.sample.rst

Volume service
~~~~~~~~~~~~~~

Now to instantiate the Volume service:

.. code-block:: php

  $service = $client->volumeService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  volumes
  volume-types
  snapshots


Glossary
--------

.. glossary::

  volume
    A volume is a detachable block storage device. You can think of it as a USB
    hard drive. It can only be attached to one instance at a time.

  volume type
    Providers may support multiple types of volumes; at Rackspace, a volume
    can either be ``SSD`` (solid state disk: expensive, high-performance) or
    ``SATA`` (serial attached storage: regular disks, less expensive).

  snapshot
    A snapshot is a point-in-time copy of the data contained in a volume.


Further links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/cbs/api/v1.0/cbs-getting-started/content/Doc_change_history_d1e060.html>`_
- `API Developer Guide <http://docs.rackspace.com/cbs/api/v1.0/cbs-devguide/content/overview.html>`_
- `API release history <http://docs.rackspace.com/cbs/api/v1.0/cbs-releasenotes/content/preface.html>`_
