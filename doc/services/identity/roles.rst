Roles
=====

A role is a personality that a user assumes when performing a specific
set of operations. A role includes a set of rights and privileges. A
user assuming a role inherits the rights and privileges associated with
the role. A token that is issued to a user includes the list of roles
the user can assume. When a user calls a service, that service
determines how to interpret a user's roles. A role that grants access to
a list of operations or resources within one service may grant access to
a completely different list when interpreted by a different service.

Useful object properties/methods
--------------------------------

+---------------+------------------------+------------------------+
| Property      | Getter                 | Setter                 |
+===============+========================+========================+
| id            | ``getId()``            | ``setId()``            |
+---------------+------------------------+------------------------+
| name          | ``getName()``          | ``setName()``          |
+---------------+------------------------+------------------------+
| description   | ``getDescription()``   | ``setDescription()``   |
+---------------+------------------------+------------------------+

List roles
----------

This call lists the global roles available within a specified service.

.. code-block:: php

  $roles = $service->getRoles();

  foreach ($roles as $role) {
     // ...
  }


Get role
--------

This call lists detailed information (id, name, description) for a
specified role.

.. code-block:: php

  $role = $service->getRole('{roleId}');


Add/delete user roles
---------------------

.. code-block:: php

  $user = $service->getUser('{userId}');

  $roleId = '{roleId}';

  // add role to user
  $user->addRole($roleId);

  // remove role from user
  $user->removeRole($roleId);


List user global roles
----------------------

This call returns a list of global roles associated with a user:

.. code-block:: php

  $roles = $user->getRoles();

  foreach ($roles as $role) {
     // ...
  }
