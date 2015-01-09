Stack resource events
=====================

Operations on resources within a stack (such as the creation of a
resource) produce events.


List stack events
-----------------

You can list all of the events for all of the resources in a stack as
shown in the following example:

.. code-block:: php

  $stackEvents = $stack->listEvents();

  foreach ($stackEvents as $stackEvent) {
      /** @var $stackEvent OpenCloud\Orchestration\Resource\Event **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/list-stack-events.php>`_


List stack resource events
--------------------------

You can list all of the events for a specific resource in a stack as
shown in the following example:

.. code-block:: php

  $resourceEvents = $resource->listEvents();

  foreach ($resourceEvents as $resourceEvent) {
      /** @var $resourceEvent OpenCloud\Orchestration\Resource\Event **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/list-stack-resource-events.php>`_


Get stack resource event
------------------------

You can retrieve a specific event for a specific resource in a stack, by
using the resource event's ID, as shown in the following example:

.. code-block:: php

  /** @var $resourceEvent OpenCloud\Orchestration\Resource\Event **/
  $resourceEvent = $resource->getEvent('c1342a0a-59e6-4413-9af5-07c9cae7d729');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-stack-resource-event.php>`_
