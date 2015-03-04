Groups
======

List all groups
---------------

.. code-block:: php

  $groups = $service->groupList();
  foreach ($group as $group) {
    /** @var $group OpenCloud\Autoscale\Resources\Group */
  }

Please consult the `iterator guide </iterators>`__ for more information about
iterators.


Retrieve group by ID
--------------------

.. code-block:: php

  $group = $service->group('{groupId}');


Create a new group
------------------

.. code-block:: php

  // Set the config object for this autoscale group; contains all of properties
  // which determine its behaviour
  $groupConfig = array(
    'name'        => 'new_autoscale_group',
    'minEntities' => 5,
    'maxEntities' => 25,
    'cooldown'    => 60,
  );

  // We need specify what is going to be launched. For now, we'll launch a new server
  $launchConfig = array(
    'type' => 'launch_server',
    'args' => array(
      'server' => array(
        'flavorRef' => 3,
        'name'      => 'webhead',
        'imageRef'  => '0d589460-f177-4b0f-81c1-8ab8903ac7d8'
      ),
      'loadBalancers' => array(
        array('loadBalancerId' => 2200, 'port' => 8081),
      )
    )
  );

  // Do we want particular scaling policies?
  $policy = array(
    'name'     => 'scale up by 10',
    'change'   => 10,
    'cooldown' => 5,
    'type'     => 'webhook',
  );

  $group->create(array(
    'groupConfiguration'  => $groupConfig,
    'launchConfiguration' => $launchConfig,
    'scalingPolicies'     => array($policy),
  ));

Delete a group
--------------

.. code-block:: php

  $group->delete();

Get the current state of the scaling group
------------------------------------------

.. code-block:: php

  $group->getState();
