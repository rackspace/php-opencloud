Iterators
=========

Iterators allow you to traverse over collections of your resources in an
efficient and easy way. Currently there are two Iterators provided by
the SDK:

-  **ResourceIterator**. The standard iterator class that implements
   SPL's standard
   `Iterator <http://php.net/manual/en/class.iterator.php>`__,
   `ArrayAccess <http://www.php.net/manual/en/class.arrayaccess.php>`__
   and `Countable <http://php.net/manual/en/class.countable.php>`__
   interfaces. In short, this allows you to traverse this object (using
   ``foreach``), count its internal elements like an array (using
   ``count`` or ``sizeof``), and access its internal elements like an
   array (using ``$iterator[1]``).

-  **PaginatedIterator**. This is a child of ResourceIterator, and as
   such inherits all of its functionality. The difference however is
   that when it reaches the end of the current collection, it attempts
   to construct a URL to access the API based on predictive paginated
   collection templates.

Common behaviour
----------------

.. code-block:: php

    $iterator = $computeService->flavorList();

There are two ways to traverse an iterator. The first is the longer,
more traditional way:

.. code-block:: php

    while ($iterator->valid()) {
        $flavor = $iterator->current();

        // do stuff..
        echo $flavor->id;

        $iterator->next();
    }

There is also a shorter and more intuitive version:

.. code-block:: php

    foreach ($iterator as $flavor) {
        // do stuff...
        echo $flavor->id;
    }

Because the iterator implements PHP's native ``Iterator`` interface, it
can inherit all the native functionality of traversible data structures
with ``foreach``.

Very important note
-------------------

Until now, users have been expected to do this:

.. code-block:: php

    while ($flavor = $iterator->next()) {
       // ...
    }

which is **incorrect**. The single responsibility of ``next`` is to move
the internal pointer forward. It is the job of ``current`` to retrieve
the current element.

For your convenience, these two Iterator classes are fully backward
compatible: they exhibit all the functionality you'd expect from a
correctly implemented iterator, but they also allow previous behaviour.

Using paginated collections
---------------------------

For large collections, such as retrieving DataObjects from
CloudFiles/Swift, you need to use pagination. Each resource will have a
different limit per page; so once that page is traversed, there needs to
be another API call to retrieve to *next* page's resources.

There are two key concepts:

-  **limit** is the amount of resources returned per page
-  **marker** is the way you define a starting point. It is some form of
   identifier that allows the collection to begin from a specific
   resource

Resource classes
~~~~~~~~~~~~~~~~

When the iterator returns a current element in the internal list, it
populates the relevant resource class with all the data returned to the
API. In most cases, a ``stdClass`` object will become an instance of
``OpenCloud\Common\PersistentObject``.

In order for this instantiation to happen, the ``resourceClass`` option
must correspond to some method in the parent class that creates the
resource. For example, if we specify 'ScalingPolicy' as the
``resourceClass``, the parent object (in this case
``OpenCloud\Autoscale\Group``, needs to have some method will allows the
iterator to instantiate the child resource class. These are all valid:

1. ``Group::scalingGroup($data);``

2. ``Group::getScalingGroup($data);``

3. ``Group::resource('ScalingGroup', $data);``

where ``$data`` is the standard object. This list runs in order of
precedence.

Setting up a PaginatedIterator
------------------------------

.. code-block:: php

    use OpenCloud\Common\Collection\PaginatedIterator;

    $service = $client->computeService();

    $flavors = PaginatedIterator::factory($service, array(
        'resourceClass'  => 'Flavor',
        'baseUrl'        => $service->getUrl('flavors')
        'limit.total'    => 350,
        'limit.page'     => 100,
        'key.collection' => 'flavors'
    ));

    foreach ($flavors as $flavor) {
        echo $flavor->getId();
    }

As you can see, there are a lot of configuration parameters to pass in -
and getting it right can be quite fiddly, involving a lot of API
research. For this reason, using the convenience methods like
``flavorList`` is recommended because it hides the complexity.

PaginatedIterator options
~~~~~~~~~~~~~~~~~~~~~~~~~

There are certain configuration options that the paginated iterator
needs to work. These are:

+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| Name                    | Description                                                                                                                                                                                                                                       | Type                         | Required   | Default       |
+=========================+===================================================================================================================================================================================================================================================+==============================+============+===============+
| resourceClass           | The resource class that is instantiated when the current element is retrieved. This is relative to the parent/service which called the iterator.                                                                                                  | string                       | Yes        | -             |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| baseUrl                 | The base URL that is used for making new calls to the API for new pages                                                                                                                                                                           | ``Guzzle\Http\Url``          | Yes        | -             |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| limit.total             | The total amount of resources you want to traverse in your collection. The iterator will stop as this limit is reached, regardless if there are more items in the list                                                                            | int                          | No         | 10000         |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| limit.page              | The amount of resources each page contains                                                                                                                                                                                                        | int                          | No         | 100           |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| key.links               | Often, API responses will contain "links" that allow easy access to the next page of a resource collection. This option specifies what that JSON element is called (its key). For example, for Rackspace Compute images it is ``images_links``.   | string                       | No         | links         |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| key.collection          | The top-level key for the array of resources. For example, servers are returned with this data structure: ``{"servers": [...]}``. The **key.collection** value in this case would be ``servers``.                                                 | string                       | No         | ``null``      |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| key.collectionElement   | Rarely used. But it indicates the key name for each nested resource element. KeyPairs, for example, are listed like this: ``{"keypairs": [ {"keypair": {...}} ] }``. So in this case the collectionElement key would be ``keypair``.              | string                       | No         | ``null``      |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| key.marker              | The value used as the marker. It needs to represent a valid property in the JSON resource objects. Often it is ``id`` or ``name``.                                                                                                                | string                       | No         | name          |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| request.method          | The HTTP method used when making API calls for new pages                                                                                                                                                                                          | string                       | No         | GET           |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| request.headers         | The HTTP headers to send when making API calls for new pages                                                                                                                                                                                      | array                        | No         | ``array()``   |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| request.body            | The HTTP entity body to send when making API calls for new pages                                                                                                                                                                                  | ``Guzzle\Http\EntityBody``   | No         | ``null``      |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
| request.curlOptions     | Additional cURL options to use when making API calls for new pages                                                                                                                                                                                | array                        | No         | ``array()``   |
+-------------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------+------------+---------------+
