 Entities
=========

Info
----

An entity is the target of what you are monitoring. For example, you can
create an entity to monitor your website, a particular web service, or
your Rackspace server. Note that an entity represents only one item in
the monitoring system -- if you wanted to monitor each server in a
cluster, you would create an entity for each of the servers. You would
not create a single entity to represent the entire cluster.

An entity can have multiple checks associated with it. This allows you
to check multiple services on the same host by creating multiple checks
on the same entity, instead of multiple entities each with a single
check.

Setup
-----

.. code:: php

    $entity = $service->getEntity();

For more information about setting up the ``$service`` object, please
see the userguide tutorial for `services <Service.md>`__.

Attributes
----------

+-----------------+-------------------------------------------------------------------------+-------------+-----------------------------------------------------+------------------------+
| Name            | Description                                                             | Required?   | Data type                                           | Method                 |
+=================+=========================================================================+=============+=====================================================+========================+
| label           | Defines a name for the entity.                                          | Required    | String (1..255 chars)                               | ``getLabel()``         |
+-----------------+-------------------------------------------------------------------------+-------------+-----------------------------------------------------+------------------------+
| agent\_id       | Agent to which this entity is bound to.                                 | Optional    | String matching the regex: ``/^[-\.\w]{1,255}$/``   | ``getAgentId()``       |
+-----------------+-------------------------------------------------------------------------+-------------+-----------------------------------------------------+------------------------+
| ip\_addresses   | Hash of IP addresses that can be referenced by checks on this entity.   | Optional    | Array                                               | ``getIpAddresses()``   |
+-----------------+-------------------------------------------------------------------------+-------------+-----------------------------------------------------+------------------------+
| metadata        | Arbitrary key/value pairs that are passed during the alerting phase.    | Optional    | ``OpenCloud\Common\Metadata``                       | ``getMetadata()``      |
+-----------------+-------------------------------------------------------------------------+-------------+-----------------------------------------------------+------------------------+

Create Entity
-------------

.. code:: php

    $service->createEntity(array(
        'label' => 'Brand New Entity',
        'ip_addresses' => array(
            'default' => '127.0.0.4',
            'b'       => '127.0.0.5',
            'c'       => '127.0.0.6',
            'test'    => '127.0.0.7'
        ),
        'metadata' => array(
            'all'  => 'kinds',
            'of'   => 'stuff',
            'can'  => 'go',
            'here' => 'null is not a valid value'
        )
    ));

Update and delete Entity
------------------------

.. code:: php

    // Update
    $entity->update(array(
        'label' => 'New label for my entity'
    ));

    // Delete
    $entity->delete();

