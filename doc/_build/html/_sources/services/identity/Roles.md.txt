Roles
=====

Intro
-----

A role is a personality that a user assumes when performing a specific
set of operations. A role includes a set of rights and privileges. A
user assuming a role inherits the rights and privileges associated with
the role. A token that is issued to a user includes the list of roles
the user can assume. When a user calls a service, that service
determines how to interpret a user's roles. A role that grants access to
a list of operations or resources within one service may grant access to
a completely different list when interpreted by a different service.

Setup
-----

Role objects are instantiated from the Identity service. For more
details, see the `Service <Service.md>`__ docs.

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

.. code:: php

    $roles = $service->getRoles();

    foreach ($roles as $role) {
       // ...
    }

For more information about how to use iterators, see the
`documentation <../Iterators.md>`__.

Get role
--------

This call lists detailed information (id, name, description) for a
specified role.

.. code:: php

    $roleId = '123abc';
    $role = $service->getRole($roleId);

Add/delete user roles
---------------------

To add/remove user roles, you must first instantiate a
`user <Users.md>`__ object:

.. code:: php

    $roleId = '123abc';

    // add role to user
    $user->addRole($roleId);

    // remove role from user
    $user->removeRole($roleId);

List user global roles
----------------------

This call returns a list of global roles associated with a user:

.. code:: php

    $roles = $user->getRoles();

    foreach ($roles as $role) {
       // ...
    }

For more information about how to use iterators, see the
`documentation <../Iterators.md>`__.
