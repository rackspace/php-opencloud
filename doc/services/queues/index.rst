Queues v1
=========

.. include:: ../common/clients.sample.rst

Queues service
~~~~~~~~~~~~~~

Now to instantiate the Queues service:

.. code-block:: php

  $service = $client->queuesService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  queues
  messages
  claims


Glossary
--------

.. glossary::

  claim
    A Claim is the process of a worker checking out a message to perform
    a task. Claiming a message prevents other workers from attempting to
    process the same messages.

  queue
    A Queue is an entity that holds messages. Ideally, a queue is created
    per work type. For example, if you want to compress files, you would
    create a queue dedicated to this job. Any application that reads from
    this queue would only compress files.

  message
    A Message is a task, a notification, or any meaningful data that a
    producer or publisher sends to the queue. A message exists until it is
    deleted by a recipient or automatically by the system based on a TTL
    (time-to-live) value.


Further links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/queues/api/v1.0/cq-gettingstarted/content/DB_Overview.html>`_
- `API Developer Guide <http://docs.rackspace.com/queues/api/v1.0/cq-devguide/content/overview.html>`_
- `API release history <http://docs.rackspace.com/queues/api/v1.0/cq-gettingstarted/content/doc-change-history.html>`_
