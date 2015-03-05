Orchestration v1
================

Setup
-----

.. include:: rs-client.sample.rst

Now set up the Orchestration service:

.. code-block:: php

  $service = $client->orchestrationService('{catalogName}', '{region}', '{urlType}');

.. include:: ../common/service-args.rst


Operations
----------

.. toctree::

  templates
  stacks
  resources
  resource-types
  build-info


Glossary
--------

.. glossary::

  template
    An Orchestration template is a JSON or YAML document
    that describes how a set of resources should be assembled to produce
    a working deployment. The template specifies what resources should be
    used, what attributes of these resources are parameterized and what
    information is output to the user when a template is instantiated.

  resource
    A resource is a template artifact that represents some
    component of your desired architecture (a Cloud Server, a group of
    scaled Cloud Servers, a load balancer, some configuration management
    system, and so forth).

  stack
    A stack is a running instance of a template. When a stack
    is created, the resources specified in the template are created.
