Alarms
======

Alarms bind alerting rules, entities, and notification plans into a
logical unit. Alarms are responsible for determining a state (``OK``,
``WARNING`` or ``CRITICAL``) based on the result of a Check, and
executing a notification plan whenever that state changes. You create
alerting rules by using the alarm DSL. For information about using the
alarm language, refer to the `reference
documentation <http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/alerts-language.html>`__.

Setup
-----

In order to interact with this feature, you must first retrieve an entity by
its ID:

.. code-block:: php

  $entity = $service->getEntity('{entityId}');

and then a particular check, about which you can configure alarms:

.. code-block:: php

  $check = $entity->getCheck('{checkId}');

For more information about these resource types, please consult the documentation
about `entities <entities>`_ and `checks <checks>`_.

Retrieve alarm
--------------

.. code-block:: php

  $alarm = $check->getAlarm('{alarmId}');


Once you have access to a ``OpenCloud\Monitoring\Resource\Alarm`` object, these
are the attributes you can access:

+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+
| Name                     | Description                                                                 | Required?   | Method                          |
+==========================+=============================================================================+=============+=================================+
| check_id                 | The ID of the check to alert on.                                            | Required    | ``getCheckId()``                |
+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+
| notification_plan_id     | The ID of the notification plan to execute when the state changes.          | Optional    | ``getNotificationPlanId()``     |
+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+
| criteria                 | The alarm DSL for describing alerting conditions and their output states.   | Optional    | ``getCriteria()``               |
+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+
| disabled                 | Disable processing and alerts on this alarm                                 | Optional    | ``isDisabled()`` <``bool``\ >   |
+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+
| label                    | A friendly label for an alarm.                                              | Optional    | ``getLabel()``                  |
+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+
| metadata                 | Arbitrary key/value pairs.                                                  | Optional    | ``getMetadata()``               |
+--------------------------+-----------------------------------------------------------------------------+-------------+---------------------------------+


Create Alarm
------------

.. code-block:: php

  $alarm = $check->getAlarm();
  $alarm->create(array(
      'check_id' => 'chAAAA',
      'criteria' => 'if (metric["duration"] >= 2) { return new AlarmStatus(OK); } return new AlarmStatus(CRITICAL);',
      'notification_plan_id' => 'npAAAAA'
  ));


List Alarms
-----------

.. code-block:: php

  $alarms = $entity->getAlarms();

  foreach ($alarms as $alarm) {
      echo $alarm->getId();
  }


Update Alarm
------------

.. code-block:: php

  $alarm->update(array(
      'criteria' => 'if (metric["duration"] >= 5) { return new AlarmStatus(OK); } return new AlarmStatus(CRITICAL);'
  ));


Delete alarm
------------

.. code-block:: php

  $alarm->delete();
