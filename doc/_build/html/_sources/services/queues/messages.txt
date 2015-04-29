Messages
========

Setup
-----

In order to work with messages, you must first retrieve a queue by its name:

.. code-block:: php

  $queue = $service->getQueue('{queueName}');


Post new message
----------------

This operation posts the specified message or messages. You can submit
up to 10 messages in a single request.

When posting new messages, you specify only the ``body`` and ``ttl`` for
the message. The API will insert metadata, such as ID and age.

How you pass through the array structure depends on whether you are
executing multiple or single  posts, but the keys are the
same:

* The ``body`` attribute specifies an arbitrary document that constitutes
  the body of the message being sent. The size of this body is limited to
  256 KB, excluding whitespace.

* The ``ttl`` attribute specifies how long the server waits before marking
  the message as expired and removing it from the queue. The value of ttl
  must be between 60 and 1209600 seconds (14 days). Note that the server
  might not actually delete the message until its age has reached up to
  (ttl + 60) seconds, to allow for flexibility in storage implementations.


Posting a single message
~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  use OpenCloud\Common\Constants\Datetime;

  $queue->createMessage(array(
      'body' => (object) array(
          'event'    => 'BackupStarted',
          'deadline' => '26.12.2013',
      ),
      'ttl'  => 2 * Datetime::DAY
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Queues/add-message.php>`_


Post a batch of messages
~~~~~~~~~~~~~~~~~~~~~~~~

Please note that the list of messages will be truncated at 10. For more,
please execute another method call.

.. code-block:: php

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


Get messages
------------

This operation gets the message or messages in the specified queue.

Message IDs and markers are opaque strings. Clients should make no
assumptions about their format or length. Furthermore, clients should
assume that there is no relationship between markers and message IDs
(that is, one cannot be derived from the other). This allows for a wide
variety of storage driver implementations.

Results are ordered by age, oldest message first.


Parameters
~~~~~~~~~~

When retrieving messages, you can filter using these options:

+--------------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Name               | Style   | Type       | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                |
+====================+=========+============+============================================================================================================================================================================================================================================================================================================================================================================================================================================================================+
| marker             | Query   | String     | Specifies an opaque string that the client can use to request the next batch of messages. The marker parameter communicates to the server which messages the client has already received. If you do not specify a value, the API returns all messages at the head of the queue (up to the limit). Optional.                                                                                                                                                                |
+--------------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| limit              | Query   | Integer    | When more messages are available than can be returned in a single request, the client can pick up the next batch of messages by simply using the URI template parameters returned from the previous call in the "next" field. Specifies up to 10 messages (the default value) to return. If you do not specify a value for the limit parameter, the default value of 10 is used. Optional.                                                                                 |
+--------------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| echo               | Query   | Boolean    | Determines whether the API returns a client's own messages. The echo parameter is a Boolean value (true or false) that determines whether the API returns a client's own messages, as determined by the uuid portion of the User-Agent header. If you do not specify a value, echo uses the default value of false. If you are experimenting with the API, you might want to set echo=true in order to see the messages that you posted. The echo parameter is optional.   |
+--------------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| include_claimed    | Query   | ​Boolean    | Determines whether the API returns claimed messages and unclaimed messages. The include\_claimed parameter is a Boolean value (true or false) that determines whether the API returns claimed messages and unclaimed messages. If you do not specify a value, include\_claimed uses the default value of false (only unclaimed messages are returned). Optional.                                                                                                           |
+--------------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

.. code-block:: php

  $messages = $queue->listMessages(array(
      'marker' => '51db6f78c508f17ddc924357',
      'limit'  => 20,
      'echo'   => true
  ));

  foreach ($messages as $message) {
      echo $message->getId() . PHP_EOL;
  }


Get a set of messages by ID
---------------------------

This operation provides a more efficient way to query multiple messages
compared to using a series of individual GET. Note that the list of IDs
cannot exceed 20. If a malformed ID or a nonexistent message ID is
provided, it is ignored, and the remaining messages are returned.

Parameters
~~~~~~~~~~

+------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------+
| Name       | Style   | Type       | Description                                                                                                                            |
+============+=========+============+========================================================================================================================================+
| ids        | Query   | String     | Specifies the IDs of the messages to get. Format multiple message ID values by separating them with commas (comma-separated). Optional |
+------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------+
| claim_id   | Query   | ​Boolean    | Specifies the claim ID with which the message is associated. Optional.                                                                 |
+------------+---------+------------+----------------------------------------------------------------------------------------------------------------------------------------+


.. code-block:: php

  $ids = array('id_1', 'id_2');

  $messages = $queue->listMessages(array('ids' => $ids));

  foreach ($messages as $message) {
      echo $message->getId() . PHP_EOL;
  }


Delete a set of messages by ID
------------------------------

This operation immediately deletes the specified messages. If any of the
message IDs are malformed or non-existent, they are ignored. The
remaining valid messages IDs are deleted.

.. code-block:: php

  $ids = array('id_1', 'id_2');
  $response = $queue->deleteMessages($ids);


Get a specific message
----------------------

This operation gets the specified message from the specified queue.

.. code-block:: php

  /** @var $message OpenCloud\Queues\Message */
  $message = $queue->getMessage('{messageId}');


Once you have access to the ``Message`` object, you access its attributes:

+-----------+-------------+--------------------------------------------------------------------------------------------------------------+
| attribute | method      | description                                                                                                  |
+===========+=============+==============================================================================================================+
| href      | ``getHref`` | An opaque relative URI that the client can use to uniquely identify a message resource and interact with it. |
+-----------+-------------+--------------------------------------------------------------------------------------------------------------+
| ttl       | ``getTtl``  | The TTL that was set on the message when it was posted. The message expires after (ttl - age) seconds.       |
+-----------+-------------+--------------------------------------------------------------------------------------------------------------+
| age       | ``getAge``  | The number of seconds relative to the server's clock.                                                        |
+-----------+-------------+--------------------------------------------------------------------------------------------------------------+
| body      | ``getBody`` | The arbitrary document that was submitted with the original request to post the message.                     |
+-----------+-------------+--------------------------------------------------------------------------------------------------------------+


Delete message
--------------

.. code-block:: php

  $message->delete();
