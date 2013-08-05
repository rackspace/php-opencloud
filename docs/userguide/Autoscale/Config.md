#Config

There are two types of configuration associated with an Autoscale group:

- Group configuration - which includes the minimum number of entities, the maximum number of entities, global cooldown, and other metadata;
- Launch configuration - which includes the minimum number of entities, the maximum number of entities, global cooldown, and other metadata.

## Setup

To interact with the configuration of a scaling group, you will need to setup the group object beforehand.

```php
$groupId = 'foobar';
$group   = $service->getGroup($groupId);
```

For more information about setting up the `$service` object, please see the userguide tutorial for [Autoscale groups]().

## Get group/launch configuration

```php
$groupConfig  = $group->getGroupConfig();
$launchConfig = $group->getLaunchConfig();
```

## Edit group/launch configuration

```php
$groupConfig->update(array(
    'name' => 'New name!'
));

$launchConfig = $group->getLaunchConfig();

$server = $launchConfig->args->server;
$server->name = "BRAND NEW SERVER NAME"; 

$launchConfig->update(array
    'args' => array(
        'server' => $server,
        'loadBalancers' => $launchConfig->args->loadBalancers
    )
));
```