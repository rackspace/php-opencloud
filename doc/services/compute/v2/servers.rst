=======
Servers
=======

Create server
-------------

.. sample:: compute/v2/servers/create.php
.. refdoc:: Rackspace/Compute/v2/Service.html#method_createServer

Waiting for builds to complete
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Some builds can take minutes to complete. To ensure your code operates on a fully built server, you can wait until it
reaches an ``ACTIVE`` state. This is a blocking operation which continually polls the API:

.. code-block:: php

    $timeout = 300; // seconds
    $server->waitUntilActive($timeout);

List servers
------------

.. sample:: compute/v2/servers/list.php
.. refdoc:: Rackspace/Compute/v2/Service.html#method_listServers

Generators
~~~~~~~~~~

Each iteration will return a :apiref:`Flavor instance <Rackspace/Compute/v2/Models/Flavor.html>`.

.. include:: /common/generators.rst

Detailed information
~~~~~~~~~~~~~~~~~~~~

By default only a small subset of information is returned for each server iteration. To enable a more detailed
representation, you can pass ``true`` into the method, like so:

.. code-block:: php

    $servers = $service->listServers(true);

Retrieve details of a server
----------------------------

.. sample:: compute/v2/servers/get.php
.. refdoc:: Rackspace/Compute/v2/Service.html#method_getServer

List server IP addresses
------------------------

.. sample:: compute/v2/servers/list_ips.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_getIpAddresses

Reboot server
-------------

.. sample:: compute/v2/servers/reboot.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_reboot

Rescue server
-------------

.. sample:: compute/v2/servers/rescue.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_rescue

Unrescue server
---------------

.. sample:: compute/v2/servers/unrescue.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_unrescue

Update server
-------------

.. sample:: compute/v2/servers/update.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_update

.. note::

    The only attributes you can update are ``name``, ``accessIPv4`` and ``accessIPv6``.

Change admin password
---------------------

.. sample:: compute/v2/servers/change_password.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_changePassword

Resize server
-------------

.. sample:: compute/v2/servers/create.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_resize

Confirm resize
~~~~~~~~~~~~~~

Once you have resized a server, you need to confirm the resize and transition it to an active state:

.. sample:: compute/v2/servers/confirm_resize.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_confirmResize

Revert resize
~~~~~~~~~~~~~

You can revert your changes at any time before confirming like so:

.. sample:: compute/v2/servers/revert_resize.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_revertResize

Delete server
-------------

.. sample:: compute/v2/servers/create.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_delete

Retrieve metadata
-----------------

.. sample:: compute/v2/servers/get_metadata.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_getMetadata

Merge new metadata with old
---------------------------

.. sample:: compute/v2/servers/merge_metadata.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_mergeMetadata

Reset all existing metadata with new
------------------------------------

.. sample:: compute/v2/servers/reset_metadata.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_resetMetadata