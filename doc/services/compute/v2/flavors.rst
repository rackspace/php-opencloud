=======
Flavors
=======

List flavors
------------

.. sample:: compute/v2/flavors/list.php
.. refdoc:: OpenStack/Compute/v2/Service.html#method_listFlavors

Each iteration will return a :apiref:`Flavor instance <Rackspace/Compute/v2/Models/Flavor.html>`.

.. include:: /common/generators.rst

Retrieve details about a flavor
-------------------------------

When retrieving a flavor, sometimes you only want to operate on it. If this is the case,
then there is no need to perform an initial GET request to the API:

.. sample:: compute/v2/flavors/get.php

If, however, you *do* want to retrieve all the details of a remote resource from the API, you just call:

.. code-block:: php

    $flavor->retrieve();

which will update the state of the local object. This gives you an element of control over your app's performance.

.. refdoc:: Rackspace/Compute/v2/Service.html#method_getFlavor

Retrieve extra specifications of a flavor
-----------------------------------------

.. sample:: compute/v2/flavors/get_extra_specs.php
.. refdoc:: Rackspace/Compute/v2/Models/Flavor.html#method_retrieveExtraSpecs