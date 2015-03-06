Monitoring v1
=============

.. include:: ../common/rs-only.sample.rst

Monitoring service
~~~~~~~~~~~~~~~~~~

Now to instantiate the Monitoring service:

.. code-block:: php

  $service = $client->monitoringService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst

Operations
----------

.. toctree::

  entities
  checks
  alarms
  agents
  changelogs
  metrics
  notifications
  views
  zones


Glossary
--------
