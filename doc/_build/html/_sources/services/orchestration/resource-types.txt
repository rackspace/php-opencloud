Resource types
==============

When you define a template, you must use resource types supported by
your cloud.

List resource types
-------------------

You can list all supported resource types as shown in the following
example:

.. code-block:: php

  $resourceTypes = $orchestrationService->listResourceTypes();
  foreach ($resourceTypes as $resourceType) {
      /** @var $resourceType OpenCloud\Orchestration\Resource\ResourceType **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/list-resource-types.php>`_


Get resource type
-----------------

You can retrieve a specific resource type's schema as shown in the
following example:

.. code-block:: php

  /** @var $resourceType OpenCloud\Orchestration\Resource\ResourceType **/
  $resourceType = $orchestrationService->getResourceType('OS::Nova::Server');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-resource-type.php>`_


Get resource type template
--------------------------

You can retrieve a specific resource type's representation as it would
appear in a template, as shown in the following example:

.. code-block:: php

  /** @var $resourceTypeTemplate string **/
  $resourceTypeTemplate = $resourceType->getTemplate();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-resource-type-template.php>`_
