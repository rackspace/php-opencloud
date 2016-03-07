==================
Virtual Interfaces
==================

Setup
-----

Since a virtual interface operates on a server, you will need to retrieve the server you want to act on first. To do
so, you will need its unique ID:

.. sample:: compute/v2/servers/get.php
.. refdoc:: Rackspace/Compute/v2/Service.html#method_getServer

Create a virtual interface
--------------------------

.. sample:: compute/v2/virtualInterfaces/create.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_createVirtualInterface

List all virtual interfaces
---------------------------

.. sample:: compute/v2/virtualInterfaces/list.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_listVirtualInterfaces

Delete a virtual interface
--------------------------

.. sample:: compute/v2/virtualInterfaces/delete.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_deleteVirtualInterface