#Policies

## Setup

To interact with the policies of a scaling group, you will need to setup the group object beforehand.

```php
$groupId = 'foobar';
$group   = $service->getGroup($groupId);
```

For more information about setting up the `$service` object, please see the userguide tutorial for [Autoscale groups]().

## Get all policies

```php
$policies = $group->getPolicies();

while ($policy = $policies->next()) {
    // do something
    echo "{$policy->name} {($policy->type)}" . PHP_EOL;
}
```

## Create a new scaling policy

Creating policies is achieved through passing an array to the `create` method.

```php
$policy = new \stdClass;
$policy->name = "NEW NAME";
$policy->change = 1;
$policy->cooldown = 150;
$policy->type = "webhook";

$group->getPolicy()->create(array($policy));

// or even:

$group->getPolicy()->create(array(
    (object) array(
        'name' => 'NEW NAME',
        'change' => 1,
        'cooldown' => 150,
        'type' => 'webhook'
    ),
    // etc.
));
```

## Get, update and delete a scaling policy

```php
$policyId = 'foobar';
$policy = $group->getPolicy($policyId);

$policy->update(array(
    'name' => 'More relevant name'
));

$policy->delete();
```

## Execute a scaling policy

```php
$policy->execute();
```