Identity v2
===========

.. include:: ../common/clients.sample.rst

Identity service
~~~~~~~~~~~~~~~~

Now to instantiate the Identity service:

.. code-block:: php

  $service = $client->identityService();


Operations
----------

.. toctree::

  tokens
  users
  tenants

Glossary
--------

.. glossary::

  token
    A token is an opaque string that represents an authorization to access
    cloud resources. Tokens may be revoked at any time and are valid for a
    finite duration.

  tenant
    A tenant is a container used to group or isolate resources and/or
    identity objects. Depending on the service operator, a tenant may map to
    a customer, account, organization, or project.

  user
    A user is a digital representation of a person, system, or service who
    consumes cloud services. Users have credentials and may be assigned
    tokens; based on these credentials and tokens, the authentication
    service validates that incoming requests are being made by the user who
    claims to be making the request, and that the user has the right to
    access the requested resources. Users may be directly assigned to a
    particular tenant and behave as if they are contained within that
    tenant.


Further Links
-------------

- `Quickstart for the API <http://docs.rackspace.com/auth/api/v2.0/auth-client-devguide/content/QuickStart-000.html>`_
- `API Developer Guide <http://docs.rackspace.com/auth/api/v2.0/auth-client-devguide/content/Overview-d1e65.html>`_
