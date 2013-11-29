## 1. Introduction

A __Message__ is a task, a notification, or any meaningful data that a producer or publisher sends to the queue. A
message exists until it is deleted by a recipient or automatically by the system based on a TTL (time-to-live) value.

## 2. Setup

A message is initialized from its parent object, a Queue:

```php
// Setup an empty object
$message = $queue->getMessage();

// or retrieve an existing one
$message = $queue->getMessage('<message_id>');
```

## 3. Post message

### 3.1 Description

This operation posts the specified message or messages. You can submit up to 10 messages in a single request.

When posting new messages, you specify only the `body` and `ttl` for the message. The API will insert metadata, such as
ID and age.

### 3.2 Parameters

How you pass through the array structure depends on whether you are executing multiple (3.3.2) or single (3.3.3) posts,
but the keys are the same.

The `body` attribute specifies an arbitrary document that constitutes the body of the message being sent. The size of
this body is limited to 256 KB, excluding whitespace.

The `ttl` attribute specifies how long the server waits before marking the message as expired and removing it from the
queue. The value of ttl must be between 60 and 1209600 seconds (14 days). Note that the server might not actually
delete the message until its age has reached up to (ttl + 60) seconds, to allow for flexibility in storage implementations.

### 3.3 Code samples

#### 3.3.1 Posting a single message

```
use OpenCloud\Common\Constants\Datetime;

$queue->createMessage(array(
    'body' => (object) array(
        'event'    => 'BackupStarted',
        'deadline' => '26.12.2013
    ),
    'ttl'  => 2 * Datetime::DAY
));
```

#### 3.3.2 Post a batch of messages

Please note that the list of messages will be truncated at 10. For more, please execute another method call.

```php
use OpenCloud\Common\Constants\Datetime;

$messages = array(
    array(
        'body' => (object) array(
            'play' => 'football'
        ),
        'ttl'  => 2 * Datetime::DAY
    ),
    array(
        'body' => (object) array(
            'play' => 'tennis'
        ),
        'ttl'  => 50 * Datetime::HOUR
    )
);

$queue->createMessages($messages);
```

## 4. Get messages

### 4.1 Description

This operation gets the message or messages in the specified queue.

Message IDs and markers are opaque strings. Clients should make no assumptions about their format or length. Furthermore,
clients should assume that there is no relationship between markers and message IDs (that is, one cannot be derived
from the other). This allows for a wide variety of storage driver implementations.

Results are ordered by age, oldest message first.

### 4.2 Parameters

A hash of options.

|Name|Style|Type|Description|
|----|-----|----|-----------|
|marker|Query|​String|Specifies an opaque string that the client can use to request the next batch of messages. The marker
parameter communicates to the server which messages the client has already received. If you do not specify a value, the
API returns all messages at the head of the queue (up to the limit). Optional.|
|limit|Query|​Integer|When more messages are available than can be returned in a single request, the client can pick up
the next batch of messages by simply using the URI template parameters returned from the previous call in the "next" field.
Specifies up to 10 messages (the default value) to return. If you do not specify a value for the limit parameter, the
default value of 10 is used. Optional.|
|echo|Query|​Boolean|Determines whether the API returns a client's own messages. The echo parameter is a Boolean value
(true or false) that determines whether the API returns a client's own messages, as determined by the uuid portion of
the User-Agent header. If you do not specify a value, echo uses the default value of false. If you are experimenting
with the API, you might want to set echo=true in order to see the messages that you posted. The echo parameter is optional.
|include_claimed|Query|​Boolean|Determines whether the API returns claimed messages and unclaimed messages. The
include_claimed parameter is a Boolean value (true or false) that determines whether the API returns claimed messages
and unclaimed messages. If you do not specify a value, include_claimed uses the default value of false
(only unclaimed messages are returned). Optional.
|----|-----|----|-----------|

### 4.3 Code sample

```php
$messages = $queue->listMessages(array(
    'marker' => '51db6f78c508f17ddc924357',
    'limit'  => 20,
    'echo'   => true
));

while ($message = $messages->next()) {
    echo $message->getId() . PHP_EOL;
}
```

## 5. Get a set of messages by ID

### 5.1 Description

This operation provides a more efficient way to query multiple messages compared to using a series of individual GET.
Note that the list of IDs cannot exceed 20. If a malformed ID or a nonexistent message ID is provided, it is ignored,
and the remaining messages are returned.

### 5.2 Parameters

A hash of options.

|Name|Style|Type|Description|
|----|-----|----|-----------|
|ids|Query|String|Specifies the IDs of the messages to get. Format multiple message ID values by separating them with
commas (comma-separated). Optional.|
|claim_id|Query|​String|Specifies the claim ID with which the message is associated. Optional.|
|----|-----|----|-----------|

### 5.3 Code sample

```php
$ids = array('51db6f78c508f17ddc924357', 'f5b8c8a7c62b0150b68a50d6');

$messages = $queue->listMessages(array('ids' => $ids));

while ($message = $messages->next()) {
    echo $message->getId() . PHP_EOL;
}
```

## 6. Delete a set of messages by ID

### 6.1 Description

This operation immediately deletes the specified messages. If any of the message IDs are malformed or non-existent,
they are ignored. The remaining valid messages IDs are deleted.

### 6.2 Parameters

An array of IDs.

### 6.3 Code sample

```php
$ids = array('51db6f78c508f17ddc924357', 'f5b8c8a7c62b0150b68a50d6');

$response = $queue->deleteMessages($ids);
```

## 7. Get a specific message

### 7.1 Description

This operation gets the specified message from the specified queue.

### 7.2 Parameters

Message ID.

### 7.3 Object properties

`href` is an opaque relative URI that the client can use to uniquely identify a message resource and interact with it.

`ttl` is the TTL that was set on the message when it was posted. The message expires after (ttl - age) seconds.

`age` is the number of seconds relative to the server's clock.

`body` is the arbitrary document that was submitted with the original request to post the message.

### 7.4 Code sample

```php
$message = $queue->getMessage('51db6f78c508f17ddc924357');
```

## 8. Delete message

### 8.1 Description

This operation immediately deletes the specified message.

### 8.2 Parameters

None.

### 8.3 Code sample

```php
$message->delete();
```