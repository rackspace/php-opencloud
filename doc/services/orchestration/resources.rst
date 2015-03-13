Stack resources
===============

A stack is made up of zero or more resources such as databases, load
balancers, and servers, and the software installed on servers.


List stack resources
--------------------

You can list all the resources for a stack as shown in the following
example:

.. code-block:: php

  $resources = $stack->listResources();

  foreach ($resources as $resource) {
      /** @var $resource OpenCloud\Orchestration\Resource\Resource **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/list-stack-resources.php>`_


Get stack resource
------------------

You can retrieve a specific resource in a stack bt using that resource's
name, as shown in the following example:

.. code-block:: php

  /** @var $resource OpenCloud\Orchestration\Resource\Resource **/
  $resource = $stack->getResource('load-balancer');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-stack-resource.php>`_


Get stack resource metadata
---------------------------

You can retrieve the metadata for a specific resource in a stack as
shown in the following example:

.. code-block:: php

  /** @var $resourceMetadata \stdClass **/
  $resourceMetadata = $resource->getMetadata();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-stack-resource-metadata.php>`_
