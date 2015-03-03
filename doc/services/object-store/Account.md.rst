Setup
-----

.. code:: php

    use OpenCloud\Rackspace;

    $client = new Rackspace(RACKSPACE_US, array(

    ));

    $service = $client->objectStoreService('cloudFiles');

View Account Details
--------------------

To see how many containers you have in your account
(X-Account-Container-Count), how many objects are in your account
(X-Account-Object-Count), and how many total bytes your account uses
(X-Account-Bytes-Used):

.. code:: php

    $account = $service->getAccount();

    // Either return the full Metadata object
    $details = $account->getDetails();

    // or individual values
    $account->getContainerCount();
    $account->getObjectCount();
    $account->getBytesUsed();

