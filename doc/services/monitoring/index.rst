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

.. glossary::

  agent
    A monitoring daemon that resides on the server being monitored. The agent
    gathers metrics based on agent checks and pushes them to Cloud Monitoring.
    The agent provides insight into your servers with checks for information
    such as load average and network usage. The agent acts as a single small
    service that runs scheduled checks and pushes metrics to the rest of Cloud
    Monitoring so the metrics can be analyzed, trigger alerts, and be archived.
    These metrics are gathered via checks using agent check types, and can be
    used with the other Cloud Monitoring primitives such as alarms.

  agent token
    An authentication token used to identify the agent when it communicates
    with Cloud Monitoring.

  alarm
    An alarm contains a set of rules that determine when the monitoring system
    sends a notification. You can create multiple alarms for the different
    checks types associated with an entity. For example, if your entity is a
    web server that hosts your company's website, you can create one alarm to
    monitor the server itself, and another alarm to monitor the website.

  check
    Checks explicitly specify how you want to monitor an entity. Once you've
    created an entity, you can configure one or more checks for it. A check is
    the foundational building block of the monitoring system, and is always
    associated with an entity. The check specifies the parts or pieces of the
    entity that you want to monitor, the monitoring frequency, how many
    monitoring zones are launching the check, and so on. It contains the
    specific details of how you are monitoring the entity.

  entity
    The object or resource that you want to monitor. It can be any object or
    device that you want to monitor. It's commonly a web server, but it might
    also be a website, a web page or a web service.

  monitoring zone
    A monitoring zone is the "launch point" of a check. When you create a
    check, you specify which monitoring zone(s) you want to launch the check
    from. This concept of a monitoring zone is similar to that of a datacenter,
    however in the monitoring system, you can think of it more as a geographical
    region.

  notification
    A notification is an informational message  sent to one or more addresses
    by the monitoring system when an alarm is triggered. You can set up
    notifications to alert a single individual or an entire team. Rackspace
    Cloud Monitoring currently supports webhooks and email for sending
    notifications.

  notification plan
    A notification plan contains a set of notification rules to execute when an
    alarm is triggered. A notification plan can contain multiple notifications
    for each of the following states:


Further links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/cm/api/v1.0/cm-getting-started/content/Introduction.html>`_
- `API Developer Guide <http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/overview.html>`_
- `API release history <http://docs.rackspace.com/cm/api/v1.0/cm-releasenotes/content/cmv1.10.html>`_
