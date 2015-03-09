Security Groups
===============

Create a security group
~~~~~~~~~~~~~~~~~~~~~~~

This operation takes one parameter, an associative array, with the
following keys:

+-------------------+--------------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------------------+
| Name              | Description                                                                    | Data type   | Required?   | Default value   | Example value                       |
+===================+================================================================================+=============+=============+=================+=====================================+
| ``name``          | A human-readable name for the security group. This name might not be unique.   | String      | Yes         | -               | ``new-webservers``                  |
+-------------------+--------------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------------------+
| ``description``   | Description of the security group.                                             | String      | No          | ``null``        | ``security group for webservers``   |
+-------------------+--------------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------------------+

You can create a security group as shown in the following example:

.. code-block:: php

  /** @var $securityGroup OpenCloud\Networking\Resource\SecurityGroup **/
  $securityGroup = $networkingService->createSecurityGroup(array(
      'name' => 'new-webservers',
      'description' => 'security group for webservers'
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/working/samples/Networking/create-security-group.php>`_

List security groups
~~~~~~~~~~~~~~~~~~~~

You can list all the security groups to which you have access as shown
in the following example:

.. code-block:: php

  $securityGroups = $networkingService->listSecurityGroups();
  foreach ($securityGroups as $securityGroup) {
      /** @var $securityGroup OpenCloud\Networking\Resource\SecurityGroup **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/working/samples/Networking/list-security-groups.php>`_

Get a security group
~~~~~~~~~~~~~~~~~~~~

You can retrieve a specific security group by using that security
group’s ID, as shown in the following example:

.. code-block:: php

  /** @var $securityGroup OpenCloud\Networking\Resource\SecurityGroup **/
  $securityGroup = $networkingService->getSecurityGroup('{secGroupId}');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/working/samples/Networking/get-security-group.php>`_

Delete a security group
~~~~~~~~~~~~~~~~~~~~~~~

You can delete a security group as shown in the following example:

.. code-block:: php

  $securityGroup->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/working/samples/Networking/delete-security-group.php>`_
