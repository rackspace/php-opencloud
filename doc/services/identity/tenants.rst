Tenants
=======

List tenants
------------

.. code-block:: php

  $tenants = $service->getTenants();

  foreach ($tenants as $tenant) {
     // ...
  }

Tenant object properties and methods
------------------------------------

Once you have a ``OpenCloud\Identity\Resource\Tenant`` object, you can retrieve
information like so:

.. code-block:: php

  $tenant->getId();
  $tenant->getName();
  $tenant->getDescription();
  $tenant->isEnabled();
