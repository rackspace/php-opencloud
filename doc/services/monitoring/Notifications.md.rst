Notifications
=============

Info
----

A notification is a destination to send an alarm; it can be a variety of
different types, and will evolve over time.

For instance, with a webhook type notification, Cloud Monitoring posts
JSON formatted data to a user-specified URL on an alert condition (Check
goes from ``OK`` -> ``CRITICAL`` and so on).

Setup
-----

.. code:: php

    $id = 'ntAAAA';
    $notification = $service->getNotification($id);

Attributes
----------

+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+
| Name      | Description                                                               | Data type                                                 | Method             |
+===========+===========================================================================+===========================================================+====================+
| details   | A hash of notification specific details based on the notification type.   | Array                                                     | ``getDetails()``   |
+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+
| label     | Friendly name for the notification.                                       | String (1..255 chars)                                     | ``getLabel()``     |
+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+
| type      | The notification type to send.                                            | String. Either ``webhook``, ``email``, or ``pagerduty``   | ``getType()``      |
+-----------+---------------------------------------------------------------------------+-----------------------------------------------------------+--------------------+

Test parameters
---------------

.. code:: php

    $params = array(
        'label' => 'My webhook #1',
        'type'  => 'webhook',
        'details' => array(
            'url' => 'http://example.com'
        )
    );

    // Test it
    $response = $notification->testParams($params);

    if ($response->status == 'Success') {
        echo $response->message;
    }

Create Notification
-------------------

.. code:: php

    $notification->create($params);

Test existing notification
--------------------------

.. code:: php

    $response = $notification->testExisting(true);
    echo $response->debug_info;

List Notifications
------------------

.. code:: php

    $notifications = $service->getNotifications();

    foreach ($notifications as $notification) {
        echo $notification->getId();
    }

Update and delete Notifications
-------------------------------

.. code:: php

    // Update
    $notification->update(array(
        'label' => 'New notification label'
    ));

    // Delete
    $notification->delete();

Notification types
==================

Info
----

Pretty self-explanatory. Rackspace Cloud Monitoring currently supports
the following notification types:

Webhook
^^^^^^^

Industry-standard web hooks, where JSON is posted to a configurable URL.
It has these attributes:

+-----------+------------------------------------------+---------------+
| Name      | Description                              | Data type     |
+===========+==========================================+===============+
| address   | Email address to send notifications to   | Valid email   |
+-----------+------------------------------------------+---------------+

Email
^^^^^

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

.. code:: php

    $type = $notification->getNotificationType();

Alternatively, you can retrieve an independent resource using the ID:

.. code:: php

    $typeId = 'pagerduty';
    $type = $service->getNotificationType($typeId);

List all possible notification types
------------------------------------

.. code:: php

    $types = $service->getNotificationTypes();

    foreach ($types as $type) {
        echo sprintf('%s %s', $type->getName(), $type->getDescription());
    }

Notification plans
==================

Info
----

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

Setup
-----

.. code:: php

    $planId = 'npAAAA';
    $plan = $service->getNotificationPlan();

Attributes
----------

+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| Name              | Description                                                        | Required?   | Data type               | Method                   |
+===================+====================================================================+=============+=========================+==========================+
| label             | Friendly name for the notification plan.                           | Required    | String (1..255 chars)   | ``getLabel()``           |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| critical\_state   | The notification list to send to when the state is ``CRITICAL``.   | Optional    | Array                   | ``getCriticalState()``   |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| ok\_state         | The notification list to send to when the state is ``OK``.         | Optional    | Array                   | ``getOkState()``         |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+
| warning\_state    | The notification list to send to when the state is ``WARNING``.    | Optional    | Array                   | ``getWarningState()``    |
+-------------------+--------------------------------------------------------------------+-------------+-------------------------+--------------------------+

Create Notification Plan
------------------------

.. code:: php

    $plan->create(array(
        'label'          => 'New Notification Plan',
        'critical_state' => array('ntAAAA'),
        'ok_state'       => array('ntBBBB'),
        'warning_state'  => array('ntCCCC')
    ));

Update and delete Notification Plan
-----------------------------------

.. code:: php

    // Update
    $plan->update(array(
        'label' => 'New label for my plan'
    ));

    // Delete
    $plan->delete();

Alarm Notification History
==========================

Info
----

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

Notification History is a sub-resource of an Alarm. For more information
about working with Alarms, please consult the relevant
`documentation <Alarms.md>`__.

Discover which Checks have a Notification History
-------------------------------------------------

This operation list checks for which alarm notification history is
available:

.. code:: php

    $checks = $alarm->getRecordedChecks();

List Alarm Notification History for a particular Check
------------------------------------------------------

.. code:: php

    $checkHistory = $alarm->getNotificationHistoryForCheck('chAAAA');

Get a particular Notification History item
------------------------------------------

.. code:: php

    $checkId  = 'chAAAA';
    $itemUuid = '646ac7b0-0b34-11e1-a0a1-0ff89fa2fa26';

    $singleItem = $history->getNotificationHistoryItem($checkId, $itemUuid);

