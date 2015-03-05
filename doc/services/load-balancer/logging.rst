Connection Logging
==================

The connection logging feature allows logs to be delivered to a Cloud Files
account every hour. For HTTP-based protocol traffic, these are Apache-style
access logs. For all other traffic, this is connection and transfer logging.

.. include:: lb-setup.sample.rst


Check Configuration
-------------------

.. code-block:: php

  // TRUE if enabled, FALSE if not
  $connectionLogging = $loadBalancer->hasConnectionLogging();


Enable Connection Logging
-------------------------

.. code-block:: php

  $loadBalancer->enableConnectionLogging(true);


Disable Connection Logging
--------------------------

.. code-block:: php

  $loadBalancer->enableConnectionLogging(false);
