## 1. Introduction

A __Claim__ is the process of a worker checking out a message to perform a task. Claiming a message prevents other
workers from attempting to process the same messages.

## 2. Setup

A Claim is initialized on its parent object, a Queue:

```php
// To intialize an empty object:
$claim = $queue->getClaim();

// or retrieve a specific claim:
$claim = $queue->getClaim('51db7067821e727dc24df754');
```

## 3. Claim messages

### 3.1 Description

This operation claims a set of messages (up to the value of the limit parameter) from oldest to newest and skips any
messages that are already claimed. If no unclaimed messages are available, the API returns a `204 No Content` message.

When a client (worker) finishes processing a message, it should delete the message before the claim expires to ensure
that the message is processed only once. As part of the delete operation, workers should specify the claim ID (which is
best done by simply using the provided href). If workers perform these actions, then if a claim simply expires, the
server can return an error and notify the worker of the race condition. This action gives the worker a chance to roll
back its own processing of the given message because another worker can claim the message and process it.

The age given for a claim is relative to the server's clock. The claim's age is useful for determining how quickly
messages are getting processed and whether a given message's claim is about to expire.

When a claim expires, it is released. If the original worker failed to process the message, another client worker can
then claim the message.

### 3.2 Attributes

The `ttl` attribute specifies how long the server waits before releasing the claim. The ttl value must be between 60 and
43200 seconds (12 hours). You must include a value for this attribute in your request.

The `grace` attribute specifies the message grace period in seconds. The value of grace value must be between 60 and
43200 seconds (12 hours). You must include a value for this attribute in your request. To deal with workers that have
stopped responding (for up to 1209600 seconds or 14 days, including claim lifetime), the server extends the lifetime of
claimed messages to be at least as long as the lifetime of the claim itself, plus the specified grace period. If a
claimed message would normally live longer than the grace period, its expiration is not adjusted.

The `limit` attribute specifies the number of messages to return, up to 20 messages. If limit is not specified, limit
 defaults to 10. The limit parameter is optional.

### 3.3 Code

```php
use OpenCloud\Common\Constants\Datetime;

$queue->claimMessages(array(
    'limit' => 15,
    'grace' => 5 * Datetime::MINUTE,
    'ttl'   => 5 * Datetime::MINUTE
));
```

## 4. Query claim

### 4.1 Description

This operation queries the specified claim for the specified queue. Claims with malformed IDs or claims that are not
found by ID are ignored.

### 4.2 Attributes

Claim ID.

### 4.3 Code

```php
$claim = $queue->getClaim('51db7067821e727dc24df754');
```

## 5. Update claim

### 5.1 Description

This operation updates the specified claim for the specified queue. Claims with malformed IDs or claims that are not
found by ID are ignored.

Clients should periodically renew claims during long-running batches of work to avoid losing a claim while processing a
message. The client can renew a claim by executing this method on a specific __Claim__ and including a new TTL. The API
 will then reset the age of the claim and apply the new TTL.

### 5.2 Attributes

See section 4.2.

### 5.3 Code

```php
use OpenCloud\Common\Constants\Datetime;

$claim->update(array(
    'ttl' => 10 * Datetime::MINUTE
));
```

## 6. Release claim

### 6.1 Description

This operation immediately releases a claim, making any remaining undeleted messages that are associated with the
claim available to other workers. Claims with malformed IDs or claims that are not found by ID are ignored.

This operation is useful when a worker is performing a graceful shutdown, fails to process one or more messages, or is
taking longer than expected to process messages, and wants to make the remainder of the messages available to other workers.

### 6.2 Attributes

See section 4.2.

### 6.3 Code

```php
$message->delete();
```