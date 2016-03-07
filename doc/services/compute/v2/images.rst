======
Images
======

Create image from server
------------------------

.. sample:: compute/v2/images/create_server_image.php
.. refdoc:: Rackspace/Compute/v2/Models/Server.html#method_createImage

Retrieve details of an image
----------------------------

When retrieving an image, sometimes you only want to operate on it. If this is the case,
then there is no need to perform an initial GET request to the API:

.. sample:: compute/v2/images/get.php

If, however, you *do* want to retrieve all the details of a remote resource from the API, you just call:

.. code-block:: php

    $image->retrieve();

which will update the state of the local object. This gives you an element of control over your app's performance.

.. refdoc:: Rackspace/Compute/v2/Service.html#method_getImage

List all images
---------------

.. sample:: compute/v2/images/list.php
.. refdoc:: Rackspace/Compute/v2/Service.html#method_listImages

Each iteration will return an :apiref:`Image instance <Rackspace/Compute/v2/Models/Image.html>`.

.. include:: /common/generators.rst

Get image metadata
------------------

.. sample:: compute/v2/images/get_metadata.php
.. refdoc:: Rackspace/Compute/v2/Models/Image.html#method_getMetadata

Merge new metadata with old
---------------------------

.. sample:: compute/v2/flavors/list.php
.. refdoc:: Rackspace/Compute/v2/Models/Image.html#method_mergeMetadata

Reset all existing metadata with new
------------------------------------

.. sample:: compute/v2/flavors/list.php
.. refdoc:: Rackspace/Compute/v2/Models/Image.html#method_resetMetadata

Delete image
------------

.. sample:: compute/v2/flavors/list.php
.. refdoc:: Rackspace/Compute/v2/Models/Image.html#method_delete