Webhooks
========

Setup
-----

In order to interact with webhooks, you must first retrieve the
details of the group and scaling policy you want to execute:

.. code-block:: php

  $group = $service->group('{groupId}');
  $policy = $group->getScalingPolicy('{policyId}');

Get all webhooks
----------------

.. code-block:: php

  $webhooks = $policy->getWebookList();

Create a new webhook
--------------------

.. code-block:: php

  $policy->createWebhooks(array(
      array(
          'name' => 'Alice',
          'metadata' => array(
              'firstKey'  => 'foo',
              'secondKey' => 'bar'
          )
      )
  ));

Get webhook
-----------

.. code-block:: php

  $webhook = $policy->getWebhook('{webhookId}');

Update webhook
--------------

.. code-block:: php

  // Update the metadata
  $metadata = $webhook->metadata;
  $metadata->thirdKey = 'blah';
  $webhook->update(array(
      'metadata' => $metadata
  ));


Delete webhook
--------------

.. code-block: php

  $webhook->delete();
