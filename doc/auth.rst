Authentication
==============

The client does not automatically authenticate against the API when it is
instantiated - it waits for an API call. When this happens, it checks
whether the current “token” has expired, and (re-)authenticates if
necessary.

You can force authentication, by calling:

.. code-block:: php

  $client->authenticate();

If the credentials are incorrect, a ``401`` error will be returned. If
credentials are correct, a ``200`` status is returned with your Service
Catalog.


Service Catalog
---------------

The Service Catalog is returned on successful authentication, and is
composed of all the different API services available to the current
tenant. All of this functionality is encapsulated in the ``Catalog``
object, which allows you greater control and interactivity.

.. code-block:: php

  /** @var OpenCloud\Common\Service\Catalog */
  $catalog = $client->getCatalog();

  // Return a list of OpenCloud\Common\Service\CatalogItem objects
  foreach ($catalog->getItems() as $catalogItem) {

      $name = $catalogItem->getName();
      $type = $catalogItem->getType();

      if ($name == 'cloudServersOpenStack' && $type == 'compute') {
          break;
      }

      // Array of OpenCloud\Common\Service\Endpoint objects
      $endpoints = $catalogItem->getEndpoints();
      foreach ($endpoints as $endpoint) {
          if ($endpoint->getRegion() == 'DFW') {
              echo $endpoint->getPublicUrl();
          }
      }
  }

As you can see, you have access to each Service’s name, type and list of
endpoints. Each endpoint provides access to the specific region, along
with its public and private endpoint URLs.
