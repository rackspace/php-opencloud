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

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-account-container-count.php>`_


Retrieve total object count
---------------------

.. code-block:: php

  $account->getObjectCount();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-account-object-count.php>`_


Retrieve total bytes used
-------------------------

.. code-block:: php

  $account->getBytesUsed();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-account-bytes-used.php>`_
