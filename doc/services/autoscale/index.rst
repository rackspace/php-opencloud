Auto Scale v2
=============

.. include:: ../common/rs-only.sample.rst

Auto Scale service
~~~~~~~~~~~~~~~~~~

Now to instantiate the Auto Scale service:

.. code-block:: php

  $service = $client->autoscaleService();

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  groups
  group-config
  policies
  webhooks


Glossary
--------

.. glossary::

  group
    The scaling group is at the heart of an Auto Scale deployment. The scaling
    group specifies the basic elements of the Auto Scale configuration. It
    manages how many servers can participate in the scaling group. It also
    specifies information related to load balancers if your configuration uses
    a load balancer.

  group configuration
    Outlines the basic elements of the Auto Scale configuration. The group
    configuration manages how many servers can participate in the scaling group.
    It sets a minimum and maximum limit for the number of entities that can be
    used in the scaling process. It also specifies information related to load
    balancers.

  launch configuration
    Creates a blueprint for how new servers will be created. The launch
    configuration specifies what type of server image will be started on
    launch, what flavor the new server is, and which load balancer the new
    server connects to.

  policy
    Auto Scale uses policies to define the scaling activity that will take
    place, as well as when and how that scaling activity will take place.
    Scaling policies specify how to modify the scaling group and its behavior.
    You can specify multiple policies to manage a scaling group.

  webhook
    A webhook is a reachable endpoint that when visited will execute a scaling
    policy for a particular scaling group.

Further Links
-------------

  - `Getting Started Guide for the API <http://docs.rackspace.com/cas/api/v1.0/autoscale-gettingstarted/content/Overview.html>`_
  - `API Developer Guide <http://docs.rackspace.com/cas/api/v1.0/autoscale-devguide/content/Overview.html>`_
  - `API release history <http://docs.rackspace.com/cas/api/v1.0/autoscale-releasenotes/content/v2.html>`_
