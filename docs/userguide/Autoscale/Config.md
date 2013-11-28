# Group configurations

##Intro

There are two types of configuration associated with an Auto Scale group:

- **Group configuration**. Outlines the basic elements of the Auto Scale configuration. The group configuration manages how many servers can participate in the scaling group. It sets a minimum and maximum limit for the number of entities that can be used in the scaling process. It also specifies information related to load balancers.

- **Launch configuration**. Creates a blueprint for how new servers will be created. The launch configuration specifies what type of server image will be started on launch, what flavor the new server is, and which load balancer the new server connects to.

## Setup

To interact with the configuration of a scaling group, you will need to setup the group object beforehand:

```php
$groupId = 'e41380ae-173c-4b40-848a-25c16d7fa83d';
$group   = $service->getGroup($groupId);
```

For more information about setting up the `$service` object, please see the userguide tutorial for [groups]().

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