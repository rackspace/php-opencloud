Session Persistence
===================

There are two types (or modes) of session persistence:

+-------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Name              | Description                                                                                                                                                                                                                       |
+===================+===================================================================================================================================================================================================================================+
| ``HTTP_COOKIE``   | A session persistence mechanism that inserts an HTTP cookie and is used to determine the destination back-end node. This is supported for HTTP load balancing only.                                                               |
+-------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``SOURCE_IP``     | A session persistence mechanism that will keep track of the source IP address that is mapped and is able to determine the destination back-end node. This is supported for HTTPS pass-through and non-HTTP load balancing only.   |
+-------------------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

.. include:: lb-setup.sample.rst


List Session Persistence Configuration
--------------------------------------

.. code-block:: php

    $sessionPersistence = $loadBalancer->sessionPersistence();

    /** @var $sessionPersistenceType null | 'HTTP_COOKIE' | 'SOURCE_IP' **/
    $sessionPersistenceType = $sessionPersistence->persistenceType;

In the example above:

-  If session persistence is enabled, the value of
   ``$sessionPersistenceType`` is the type of session persistence:
   either ``HTTP_COOKIE`` or ``SOURCE_IP``.
-  If session persistence is disabled, the value of
   ``$sessionPersistenceType`` is ``null``.


Enable Session Persistence
--------------------------

.. code-block:: php

  $sessionPersistence = $loadBalancer->sessionPersistence();
  $sessionPersistence->update(array(
      'persistenceType' => 'HTTP_COOKIE'
  ));


Disable Session Persistence
---------------------------

.. code-block:: php

  $sessionPersistence = $loadBalancer->sessionPersistence();
  $sessionPersistence->delete();
