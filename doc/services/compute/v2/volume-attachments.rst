==================
Volume Attachments
==================

Setup
-----

Since a volume attachment operates on a server, you will need to retrieve the server you want to act on first. To do
so, you will need its unique ID:

.. sample:: compute/v2/servers/get.php
.. refdoc:: Rackspace/Compute/v2/Service.html#method_getServer

Attach a volume
---------------

.. sample:: compute/v2/volumeAttachments/attach.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_attachVolume

Detach a volume
---------------

.. sample:: compute/v2/volumeAttachments/detach.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_detachVolume

Retrieve details about a volume attachment
------------------------------------------

.. sample:: compute/v2/volumeAttachments/get.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_getVolumeAttachment

List volume attachments
-----------------------

.. sample:: compute/v2/volumeAttachments/list.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_listVolumeAttachments