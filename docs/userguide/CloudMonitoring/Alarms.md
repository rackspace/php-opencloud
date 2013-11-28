# Alarms

## Info

Alarms bind alerting rules, entities, and notification plans into a logical unit. Alarms are responsible for determining a state (```OK```, ```WARNING``` or ```CRITICAL```) based on the result of a Check, and executing a notification plan whenever that state changes. You create alerting rules by using the alarm DSL. For information about using the alarm language, refer to the [reference documentation](http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/alerts-language.html).

## Setup

Alarms are sub-resources of Entities:

```php
$alarmId = 'alAAAA';
$alarm = $check->getAlarm();
```

For more information about working with Checks, please see the [appropriate documentation](Checks.md).

## Attributes

Name|Description|Required?|Method
---|---|---|---
check_id|The ID of the check to alert on.|Required|`getCheckId()`
notification_plan_id|The ID of the notification plan to execute when the state changes.|Optional|`getNotificationPlanId()`
criteria|The alarm DSL for describing alerting conditions and their output states.|Optional|`getCriteria()`
disabled|Disable processing and alerts on this alarm|Optional|`isDisabled()` <`bool`>
label|A friendly label for an alarm.|Optional|`getLabel()`
metadata|Arbitrary key/value pairs.|Optional|`getMetadata()`

## Create Alarm
```php
$alarm->create(array(
	'check_id' => 'chAAAA',
	'criteria' => 'if (metric["duration"] >= 2) { return new AlarmStatus(OK); } return new AlarmStatus(CRITICAL);',
	'notification_plan_id' => 'npAAAAA'
));
```

## List Alarms
```php
$alarms = $entity->getAlarms();

foreach ($alarms as $alarm) {
	echo $alarm->getId();
}
```

## Update and delete Alarm
```php
// Update
$alarm->update(array(
	'criteria' => 'if (metric["duration"] >= 5) { return new AlarmStatus(OK); } return new AlarmStatus(CRITICAL);'
));

// Delete
$alarm->delete();
```