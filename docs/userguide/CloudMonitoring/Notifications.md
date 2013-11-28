# Notifications

## Info

A notification is a destination to send an alarm; it can be a variety of different types, and will evolve over time.

For instance, with a webhook type notification, Cloud Monitoring posts JSON formatted data to a user-specified URL on an alert condition (Check goes from `OK` -> `CRITICAL` and so on).

## Setup

```php
$id = 'ntAAAA';
$notification = $service->getNotification($id);
```

## Attributes

Name|Description|Data type|Method
---|---|---|---|---
details|A hash of notification specific details based on the notification type.|Array|`getDetails()`
label|Friendly name for the notification.|String (1..255 chars)|`getLabel()`
type|The notification type to send.|String. Either `webhook`, `email`, or `pagerduty`|`getType()`

## Test parameters
```php
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
```

## Create Notification

```php
$notification->create($params);
```

## Test existing notification
```php
$response = $notification->testExisting(true);
echo $response->debug_info;
```

## List Notifications
```php
$notifications = $service->getNotifications();

foreach ($notifications as $notification) {
	echo $notification->getId();
}
```

## Update and delete Notification
```php
// Update
$notification->update(array(
	'label' => 'New notification label'
));

// Delete
$notification->delete();
```

# Notification types

## Info

Pretty self-explanatory. Rackspace Cloud Monitoring currently supports the following notification types:

#### Webhook
Industry-standard web hooks, where JSON is posted to a configurable URL. It has these attributes:

Name|Description|Data type
---|---|---
address|Email address to send notifications to|Valid email

#### Email
Email alerts where the message is delivered to a specified address. It has these attributes:

Name|Description|Data type
---|---|---
url|An HTTP or HTTPS URL to POST to|Valid URL

## Setup

If you've already set up a main Notification object, and want to access functionality for this Notification's particular Notification Type, you can access its property:

```php
$notificationType = $notification->type;
```

This will be encapsulated in its own object. Alternatively, you can instantiate a fresh resource object:

```php
$notificationType = $monitoringService->resource('notificationType');
```

## List all possible notification types
```php
$list = $notificationType->listAll();

while ($notificationType = $list->Next()) {
	echo "{$notificationType->name} ({$notificationType->description})" . PHP_EOL;
}
```

# Notification plans

## Info

A notification plan contains a set of notification actions that Rackspace Cloud Monitoring executes when triggered by an alarm. Rackspace Cloud Monitoring currently supports webhook and email notifications.

Each notification state can contain multiple notification actions. For example, you can create a notification plan that hits a webhook/email to notify your operations team if a warning occurs. However, if the warning escalates to an Error, the notification plan could be configured to hit a different webhook/email that triggers both email and SMS messages to the operations team. The notification plan supports the following states:

- Critical
- Warning
- OK

A notification plan, `npTechnicalContactsEmail`, is provided by default which will email all of the technical contacts on file for an account whenever there is a state change.

## Setup

```php
$planId = 'npAAAA';
$plan = $service->getNotificationPlan();
```

### Attributes

Name|Description|Required?|Data type|Method
---|---|---|---|---
label|Friendly name for the notification plan.|Required|String (1..255 chars)|`getLabel()`
critical_state|The notification list to send to when the state is `CRITICAL`.|Optional|Array|`getCriticalState()`
ok_state|The notification list to send to when the state is `OK`.|Optional|Array|`getOkState()`
warning_state|The notification list to send to when the state is `WARNING`.|Optional|Array|`getWarningState()`

### Create notification plan
```php
$plan->create(array(
	'label' => 'New Notification Plan',
    'critical_state' => array(
        'ntAAAA'
    ),
    'ok_state' => array(
    	'ntBBBB'
    ),
    'warning_state' => array(
    	'ntCCCC'
    )
));
```

### Get, update and delete notification plan
```php
$plan->id = 'notificationPlanId';

// Get data
$plan->get();

// Update
$plan->update(array(
	'label' => 'New label for my plan'
));

// Delete
$plan->delete();
```

# Alarm notification history

The monitoring service keeps a record of notifications sent for each alarm. This history is further subdivided by the check on which the notification occurred. Every attempt to send a notification is recorded, making this history a valuable tool in diagnosing issues with unreceived notifications, in addition to offering a means of viewing the history of an alarm's statuses.

Alarm notification history is accessible as a Time Series Collection. By default alarm notification history is stored for 30 days and the API queries the last 7 days of information.

### Setup

```php
require_once 'path/to/lib/php-opencloud.php';

use OpenCloud\OpenStack;
use OpenCloud\CloudMonitoring\Service;

$connection = new OpenStack(
	RACKSPACE_US, // Set to whatever
	array(
		'username' => 'foo',
		'password' => 'bar'
	)
);

$monitoringService = new Service($connection);
```

Please be aware that Notification History is a sub-resources of Entities **and** Alarms, so you will need to associate a Metric to its parent Alarm (with its own parent Entity) before exploiting its functionality.

```php
// Find grandparent object (i.e. an Entity)
$entity = $monitoringService->resource('entity');
$entity->get('enAAAA'); // Get by ID

// Find parent object (i.e. an Alarm)
$alarm = $monitoringService->resource('alarm');
$alarm->setParent($entity); // Associate first
$alarm->get('alAAAA'); // Get by ID

$history = $monitoringService->resource('notificationHistory');
$history->setParent($alarm); // Associate
```

### Discover notification history
```php
$checks = $history->listChecks;
```

### List Alarm Notification History for a check
```php
$checkHistory = $history->listHistory('chAAAA');
```

### Get an item of history
```php
$singleItem = $history->getSingleHistoryItem('chAAAA', '646ac7b0-0b34-11e1-a0a1-0ff89fa2fa26');
```