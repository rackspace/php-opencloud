Monitoring v1
=============

Setup
-----

.. include:: ../common/rs-client.sample.rst

Now, set up the Cloud Monitoring service:

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
