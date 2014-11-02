# Webhooks

## Setup

To interact with the webhooks of a group's scaling policy, you will need to setup the group and policy objects beforehand.

```php
$groupId  = 'foo';
$policyId = 'bar';

$group  = $service->getGroup($groupId);
$policy = $group->getPolicy($policyId);
```

For more information about setting up the `$service` object, please see the userguide tutorial for [Autoscale groups]().

## Get all webhooks

```php
$webhooks = $policy->getWebookList();
```

## Create a new webhook

```php
$policy->getWebhook()->create(array(
    (object) array(
        'name' => 'Alice',
        'metadata' => array(
            'firstKey'  => 'foo',
            'secondKey' => 'bar'
        )
    )
));
```

## Get, update and delete an individual webhook

```php
$webhookId = 'baz';
$webhook   = $policy->getWebhook($webhookId);

// Update the metadata
$metadata = $webhook->metadata;
$metadata->thirdKey = 'blah';
$webhook->update(array(
    'metadata' => $metadata
));

// Delete it
$webhook->delete();
```