Account Details
===============

To see how many containers you have in your account
(X-Account-Container-Count), how many objects are in your account
(X-Account-Object-Count), and how many total bytes your account uses
(X-Account-Bytes-Used):

Setup
-----

.. code-block:: php

  $account = $service->getAccount();


View all details
----------------

.. code-block:: php

  $details = $account->getDetails();


Retrieve total container count
------------------------

.. code-block:: php

  $account->getContainerCount();


Retrieve total object count
---------------------

.. code-block:: php

  $account->getObjectCount();


Retrieve total bytes used
-------------------------

.. code-block:: php

  $account->getBytesUsed();
