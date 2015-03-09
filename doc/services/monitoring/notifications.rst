Notifications
=============

A notification is a destination to send an alarm; it can be a variety of
different types, and will evolve over time.

For instance, with a webhook type notification, Cloud Monitoring posts
JSON formatted data to a user-specified URL on an alert condition (Check
goes from ``OK`` -> ``CRITICAL`` and so on).

Get notification
----------------

.. code-block:: php

  $notification = $service->getNotification('{id}');

Once you have access to a ``OpenCloud\Monitoring\Resource\Notification`` object,
these are the attributes available for use:

+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+
| Name      | Description                                                               | Data type                                                 | Method             |
+===========+===========================================================================+===========================================================+====================+
| details   | A hash of notification specific details based on the notification type.   | Array                                                     | ``getDetails()``   |
+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+
| label     | Friendly name for the notification.                                       | String (1..255 chars)                                     | ``getLabel()``     |
+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+
| type      | The notification type to send.                                            | String. Either ``webhook``, ``email``, or ``pagerduty``   | ``getType()``      |
+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+

Creating notifications
----------------------

The first thing to do when creating a new notification is configure the
parameters which will define the behaviour of your resource:

.. code-block:: php

  $params = array(
      'label' => 'My webhook #1',
      'type'  => 'webhook',
      'details' => array(
          'url' => 'http://example.com'
      )
  );


Test parameters
~~~~~~~~~~~~~~~

Once this is done, it is often useful to test them out to check whether they
will result in a successful creation:

.. code-block:: php

  // Test it
  $response = $notification->testParams($params);

  if ($response->status == 'Success') {
      echo $response->message;
  }


Send parameters
~~~~~~~~~~~~~~~

When you're happy with the parameters you've defined, you can complete the
operation by sending them to the API like so:

.. code-block:: php

  $notification->create($params);


Test existing notification
--------------------------

.. code-block:: php

  $response = $notification->testExisting(true);
  echo $response->debug_info;


List Notifications
------------------

.. code-block:: php

  $notifications = $service->getNotifications();

  foreach ($notifications as $notification) {
      echo $notification->getId();
  }


Update a Notification
---------------------

.. code-block:: php

  $notification->update(array(
      'label' => 'New notification label'
  ));


Delete a Notification
---------------------

.. code-block:: php

  $notification->delete();


Notification types
==================

Rackspace Cloud Monitoring currently supports the following notification types:

Webhook
~~~~~~~

Industry-standard web hooks, where JSON is posted to a configurable URL.
It has these attributes:

+-----------+------------------------------------------+---------------+
| Name      | Description                              | Data type     |
+===========+==========================================+===============+
| address   | Email address to send notifications to   | Valid email   |
+-----------+------------------------------------------+---------------+

Email
~~~~~

Email alerts where the message is delivered to a specified address. It
has these attributes:

+--------+-----------------------------------+-------------+
| Name   | Description                       | Data type   |
+========+===================================+=============+
| url    | An HTTP or HTTPS URL to POST to   | Valid URL   |
+--------+-----------------------------------+-------------+


Setup
-----

If you've already set up a main Notification object, and want to access
functionality for this Notification's particular Notification Type, you
can access its property:

.. code-block:: php

  $type = $notification->getNotificationType();

Alternatively, you can retrieve an independent resource using the ID:

.. code-block:: php

  $typeId = 'pagerduty';
  $type = $service->getNotificationType($typeId);


List all possible notification types
------------------------------------

.. code-block:: php

  $types = $service->getNotificationTypes();

  foreach ($types as $type) {
      echo sprintf('%s %s', $type->getName(), $type->getDescription());
  }


Notification plans
==================

A notification plan contains a set of notification actions that
Rackspace Cloud Monitoring executes when triggered by an alarm.
Rackspace Cloud Monitoring currently supports webhook and email
notifications.

Each notification state can contain multiple notification actions. For
example, you can create a notification plan that hits a webhook/email to
notify your operations team if a warning occurs. However, if the warning
escalates to an Error, the notification plan could be configured to hit
a different webhook/email that triggers both email and SMS messages to
the operations team. The notification plan supports the following
states:

-  Critical
-  Warning
-  OK

A notification plan, ``npTechnicalContactsEmail``, is provided by
default which will email all of the technical contacts on file for an
account whenever there is a state change.

Get a notification plan
-----------------------

.. code-block:: php

  $plan = $service->getNotificationPlan('{planId}');

Once you have access to a ``OpenCloud\\Monitoring\\Resource\\NotificationPlan``
object, you can access these resources:

+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| Name              | Description                                                        | Required?   | Data type               | Method                   |
+===================+====================================================================+=============+=========================+==========================+
| label             | Friendly name for the notification plan.                           | Required    | String (1..255 chars)   | ``getLabel()``           |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| critical_state    | The notification list to send to when the state is ``CRITICAL``.   | Optional    | Array                   | ``getCriticalState()``   |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| ok_state          | The notification list to send to when the state is ``OK``.         | Optional    | Array                   | ``getOkState()``         |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| warning_state     | The notification list to send to when the state is ``WARNING``.    | Optional    | Array                   | ``getWarningState()``    |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+

Create Notification Plan
------------------------

.. code-block:: php

  $plan->create(array(
      'label'          => 'New Notification Plan',
      'critical_state' => array('ntAAAA'),
      'ok_state'       => array('ntBBBB'),
      'warning_state'  => array('ntCCCC')
  ));


Update notification plan
------------------------

.. code-block:: php

  $plan->update(array(
      'label' => 'New label for my plan'
  ));


Delete notification plan
------------------------

.. code-block:: php

  $plan->delete();


Alarm Notification History
==========================

The monitoring service keeps a record of notifications sent for each
alarm. This history is further subdivided by the check on which the
notification occurred. Every attempt to send a notification is recorded,
making this history a valuable tool in diagnosing issues with unreceived
notifications, in addition to offering a means of viewing the history of
an alarm's statuses.

Alarm notification history is accessible as a Time Series Collection. By
default alarm notification history is stored for 30 days and the API
queries the last 7 days of information.

Setup
------

In order to interact with this feature, you must first retrieve an entity by
its ID:

.. code-block:: php

  $entity = $service->getEntity('{entityId}');

and then a particular check, about which you can configure alarms:

.. code-block:: php

  $check = $entity->getCheck('{checkId}');

and finally, retrieve the alarm:

.. code-block:: php

  $alarm = $check->getAlarm('{alarmId}');

For more information about these resource types, please consult the documentation
about `entities <entities>`_ and `checks <checks>`_.


Discover which Checks have a Notification History
-------------------------------------------------

This operation list checks for which alarm notification history is
available:

.. code-block:: php

  $checks = $alarm->getRecordedChecks();


List Alarm Notification History for a particular Check
------------------------------------------------------

.. code-block:: php

  $checkHistory = $alarm->getNotificationHistoryForCheck('chAAAA');


Get a particular Notification History item
------------------------------------------

.. code-block:: php

  $checkId  = 'chAAAA';
  $itemUuid = '646ac7b0-0b34-11e1-a0a1-0ff89fa2fa26';

  $singleItem = $history->getNotificationHistoryItem($checkId, $itemUuid);
