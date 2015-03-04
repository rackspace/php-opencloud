Group configurations
====================

Setup
-----

In order to interact with the functionality of a group's configuration,
you must first retrieve the details of the group itself. To do this, you must
substitute `{groupId}` for your group's ID:

.. code-block:: php

    $group = $service->group('{groupId}');


Get group configuration
-----------------------

.. code-block:: php

    /** @var  */
    $groupConfig = $group->getGroupConfig();


Edit group configuration
------------------------

.. code-block:: php

    $groupConfig->update(array(
        'name' => 'New name!'
    ));


Get launch configuration
------------------------

.. code-block:: php

    /** @var */
    $launchConfig = $group->getLaunchConfig();


Edit group/launch configuration
-------------------------------

.. code-block:: php

    $launchConfig = $group->getLaunchConfig();

    $server = $launchConfig->args->server;
    $server->name = "BRAND NEW SERVER NAME";

    $launchConfig->update(array
        'args' => array(
            'server' => $server,
            'loadBalancers' => $launchConfig->args->loadBalancers
        )
    ));
