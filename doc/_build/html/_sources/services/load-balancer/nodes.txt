Nodes
=====

.. include:: lb-setup.sample.rst

List Nodes
----------

You can list the nodes attached to a load balancer:

.. code-block:: php

  $nodes = $loadBalancer->nodeList();

  foreach ($nodes as $node) {
      /** @var $node OpenCloud\LoadBalancer\Resource\Node **/
  }


Add Nodes
---------

You can attach additional nodes to a load balancer. Assume
``$loadBalancer`` already has two nodes attached to it - ``$serverOne``
and ``$serverTwo`` - and you want to attach a third node to it, say
``$serverThree``, which provides a service on port 8080.

**Important:** Remember to call ``$loadBalancer->addNodes()`` after all
the calls to ``$loadBalancer->addNode()`` as shown below.

.. code-block:: php

    $address = $serverThree->addresses->private[0]->addr;
    $loadBalancer->addNode($address, 8080);
    $loadBalancer->addNodes();


The signature for ``addNodes`` is as follows:

.. function:: addNodes($address, $port[, $condition = 'ENABLED'[, $type = null[, $weight = null]]])

  Add a node to a load balancer

  :param string $address: the IP address of the node
  :param integer $port: the port number of the node
  :param string $condition: the initial condition of the code. Defaults to ``ENABLED``
  :param string $type: either ``PRIMARY`` or ``SECONDARY``
  :param integer $weight: the node weight (for round-robin algorithm)

The ``addNode`` method accepts three more optional parameters, in
addition to the two shown above:

Modify Nodes
------------

You can modify one or more of the following node attributes:

-  ``condition``: The condition of the load balancer:

   -  ``ENABLED`` – Node is ready to receive traffic from the load
      balancer.
   -  ``DISABLED`` – Node should not receive traffic from the load
      balancer.
   -  ``DRAINING`` – Node should process any traffic it is already
      receiving but should not receive any further traffic from the load
      balancer.

-  ``type``: The type of the node:

   -  ``PRIMARY`` – Nodes defined as PRIMARY are in the normal rotation
      to receive traffic from the load balancer.
   -  ``SECONDARY`` – Nodes defined as SECONDARY are only in the
      rotation to receive traffic from the load balancer when all the
      primary nodes fail.

-  ``weight``: The weight, between 1 and 100, given to node when
   distributing traffic using either the ``WEIGHTED_ROUND_ROBIN`` or the
   ``WEIGHTED_LEAST_CONNECTIONS`` load balancing algorithm.

.. code-block:: php

  use OpenCloud\LoadBalancer\Enum\NodeCondition;
  use OpenCloud\LoadBalancer\Enum\NodeType;

  $node->update(array(
      'condition' => NodeCondition::DISABLED,
      'type'      => NodeType::SECONDARY
  ));


Remove Nodes
------------

There are two ways to remove a node. The first way is on an
``OpenCloud\LoadBalancer\Resource\Node`` instance, like so:


.. code-block:: php

    $node->delete();

The second is with an ``OpenCloud\LoadBalancer\Resource\LoadBalancer``
instance and the node's ID, like so:

.. code-block:: php

    $loadBalancer->removeNode('{nodeId}');

where '{nodeId}' is the integer ID of the node itself - this is a required value.


View Node Service Events
------------------------

You can view events associated with the activity between a node and a
load balancer:

.. code-block:: php

  $nodeEvents = $loadBalancer->nodeEventList();

  foreach ($nodeEvents as $nodeEvent) {
      /** @var $nodeEvent OpenCloud\LoadBalancer\Resource\NodeEvent **/
  }
