Scaling Policies
================

Setup
-----

In order to interact with the functionality of a group's scaling
policies, you must first retrieve the details of the group itself. To do this,
you must substitute `{groupId}` for your group's ID:

.. code-block:: php

    $group = $service->group('{groupId}');


Get all policies
----------------

.. code-block:: php

  $policies = $group->getScalingPolicies();

  foreach ($policies as $policy) {
      printf("Name: %s Type: %s\n", $policy->name, $policy->type);
  }


Create new scaling policies
---------------------------

Creating policies is achieved through passing an array to the ``create``
method.

.. code-block:: php

  $policies = array(
    array(
      'name'     => 'NEW NAME',
      'change'   => 1,
      'cooldown' => 150,
      'type'     => 'webhook',
    )
  );

  $group->createScalingPolicies($policies);


Get an existing scaling policy
------------------------------

.. code-block:: php

  $policy = $group->getScalingPolicy('{policyId}');


Update a scaling policy
-----------------------

.. code-block:: php

  $policy = $group->getScalingPolicy('{policyId}');
  $policy->update(array(
      'name' => 'More relevant name'
  ));


Delete a scaling policy
-----------------------

.. code-block:: php

  $policy = $group->getScalingPolicy('{policyId}');
  $policy->delete();

Execute a scaling policy
------------------------

.. code-block:: php

  $policy = $group->getScalingPolicy('{policyId}');
  $policy->execute();
