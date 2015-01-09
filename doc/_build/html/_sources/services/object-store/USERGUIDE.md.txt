The Complete User Guide to the Object Store Service
===================================================

**Object Store** is an object-based storage system that stores content
and metadata as objects in a cloud.

Prerequisites
-------------

Client
~~~~~~

To use the object store service, you must first instantiate a
``OpenStack`` or ``Rackspace`` client object.

-  If you are working with a vanilla OpenStack cloud, instantiate an
   ``OpenCloud\OpenStack`` client as shown below.

   .. code:: php

       use OpenCloud\OpenStack;

       $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
           'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
           'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
       ));

-  If you are working with the Rackspace cloud, instantiate a
   ``OpenCloud\Rackspace`` client as shown below.

   .. code:: php

       use OpenCloud\Rackspace;

       $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
           'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
           'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
       ));

Object Store Service
~~~~~~~~~~~~~~~~~~~~

All operations on the object store are done via an object store service
object.

.. code:: php

    $region = 'DFW';
    $objectStoreService = $client->objectStoreService(null, $region);

In the example above, you are connecting to the ``DFW`` region of the
cloud. Any containers and objects created with this
``$objectStoreService`` instance will be stored in that cloud region.

Containers
----------

A **container** defines a namespace for **objects**. An object with the
same name in two different containers represents two different objects.

For example, you may create a container called ``logos`` to hold all the
image files for your blog.

A container may contain zero or more objects in it.

    **Note:** when working with names that contain non-standard
    alphanumerical characters (such as spaces or non-English
    characters), you must ensure they are encoded with
    ```urlencode`` <http://php.net/urlencode>`__ before passing them in

Create Container
~~~~~~~~~~~~~~~~

.. code:: php

    $container = $objectStoreService->createContainer('logos');

[ `Get the executable PHP script for this
example </samples/ObjectStore/create-container.php>`__ ]

Get Container Details
~~~~~~~~~~~~~~~~~~~~~

You can retrieve a single container's details by using its name. An
instance of ``OpenCloud\ObjectStore\Resource\Container`` is returned.

.. code:: php

    $container = $objectStoreService->getContainer('logos');

    /** @var $container OpenCloud\ObjectStore\Resource\Container **/

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-container.php>`__ ]

List Containers
~~~~~~~~~~~~~~~

You can retrieve a list of all your containers. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $containers = $objectStoreService->listContainers();
    foreach ($containers as $container) {
        /** @var $container OpenCloud\ObjectStore\Resource\Container  **/
    }

[ `Get the executable PHP script for this
example </samples/ObjectStore/list-containers.php>`__ ]

Set or Update Container Metadata
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can set metadata on a container.

.. code:: php

    $container->saveMetadata(array(
        'author' => 'John Doe'
    ));

[ `Get the executable PHP script for this
example </samples/ObjectStore/set-container-metadata.php>`__ ]

Get Container Metadata
~~~~~~~~~~~~~~~~~~~~~~

You can retrieve the metadata for a container.

.. code:: php

    $containerMetadata = $container->getMetadata();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-container-metadata.php>`__ ]

Delete Container
~~~~~~~~~~~~~~~~

When you no longer have a need for the container, you can remove it.

If the container is empty (that is, it has no objects in it), you can
remove it as shown below:

.. code:: php

    $container->delete();

[ `Get the executable PHP script for this
example </samples/ObjectStore/delete-container.php>`__ ]

If the container is not empty (that is, it has objects in it), you have
two choices in how to remove it:

-  `Individually remove each object <#delete-object>`__ in the
   container, then remove the container itself as shown above, or

-  Remove the container and all the objects within it as shown below:

   .. code:: php

       $container->delete(true);

   [ `Get the executable PHP script for this
   example </samples/ObjectStore/delete-container-recursive.php>`__ ]

Get Object Count
~~~~~~~~~~~~~~~~

You can quickly find out how many objects are in a container.

.. code:: php

    $containerObjectCount = $container->getObjectCount();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-container-object-count.php>`__ ]

In the example above, ``$containerObjectCount`` will contain the number
of objects in the container represented by ``$container``.

Get Bytes Used
~~~~~~~~~~~~~~

You can quickly find out the space used by a container, in bytes.

.. code:: php

    $containerSizeInBytes = $container->getBytesUsed();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-container-bytes-used.php>`__ ]

In the example above, ``$containerSizeInBytes`` will contain the space
used, in bytes, by the container represented by ``$container``.

Container Quotas
~~~~~~~~~~~~~~~~

Set Quota for Number of Objects
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can set a quota for the maximum number of objects that may be stored
in a container.

.. code:: php

    $maximumNumberOfObjectsAllowedInContainer = 25;
    $container->setCountQuota($maximumNumberOfObjectsAllowedInContainer);

[ `Get the executable PHP script for this
example </samples/ObjectStore/set-container-count-quota.php>`__ ]

Set Quota for Total Size of Objects
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can set a quota for the maximum total space (in bytes) used by
objects in a container.

.. code:: php

    use OpenCloud\Common\Constants\Size;

    $maximumTotalSizeOfObjectsInContainer = 5 * Size::GB;
    $container->setBytesQuota($maximumTotalSizeOfObjectsInContainer);

[ `Get the executable PHP script for this
example </samples/ObjectStore/set-container-bytes-quota.php>`__ ]

Get Quota for Number of Objects
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can retrieve the quota for the maximum number of objects that may be
stored in a container.

.. code:: php

    $maximumNumberOfObjectsAllowedInContainer = $container->getCountQuota();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-container-count-quota.php>`__ ]

Get Quota for Total Size of Objects
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

You can retrieve the quota for the maximum total space (in bytes) used
by objects in a container.

.. code:: php

    $maximumTotalSizeOfObjectsAllowedInContainer = $container->getBytesQuota();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-container-bytes-quota.php>`__ ]

Objects
-------

An **object** (sometimes referred to as a file) is the unit of storage
in an Object Store. An object is a combination of content (data) and
metadata.

For example, you may upload an object named ``php-elephant.jpg``, a JPEG
image file, to the ``logos`` container. Further, you may assign metadata
to this object to indicate that the author of this object was someone
named Jane Doe.

    **Note:** when working with names that contain non-standard
    alphanumerical characters (such as spaces or non-English
    characters), you must ensure they are encoded with
    ```urlencode`` <http://php.net/urlencode>`__ before passing them in

Upload Object
~~~~~~~~~~~~~

Once you have created a container, you can upload objects to it.

.. code:: php

    $localFileName  = '/path/to/local/php-elephant.jpg';
    $remoteFileName = 'php-elephant.jpg';

    $fileData = fopen($localFileName, 'r');
    $container->uploadObject($remoteFileName, $fileData);

[ `Get the executable PHP script for this
example </samples/ObjectStore/upload-object.php>`__ ]

In the example above, an image file from the local filesystem
(``path/to/local/php-elephant.jpg``) is uploaded to a container in the
Object Store.

Note that while we call ``fopen`` to open the file resource, we do not
call ``fclose`` at the end. The file resource is automatically closed
inside the ``uploadObject`` call.

It is also possible to upload an object and associate metadata with it.

.. code:: php

    use OpenCloud\ObjectStore\Resource\DataObject;

    $localFileName  = '/path/to/local/php-elephant.jpg';
    $remoteFileName = 'php-elephant.jpg';
    $metadata = array('author' => 'Jane Doe');

    $customHeaders = array();
    $metadataHeaders = DataObject::stockHeaders($metadata);
    $allHeaders = $customHeaders + $metadataHeaders;

    $fileData = fopen($localFileName, 'r');
    $container->uploadObject($remoteFileName, $fileData, $allHeaders);

[ `Get the executable PHP script for this
example </samples/ObjectStore/upload-object-with-metadata.php>`__ ]

Note that while we call ``fopen`` to open the file resource, we do not
call ``fclose`` at the end. The file resource is automatically closed
inside the ``uploadObject`` call.

Pseudo-hierarchical Folders
^^^^^^^^^^^^^^^^^^^^^^^^^^^

Although you cannot nest directories in an Object Store, you can
simulate a hierarchical structure within a single container by adding
forward slash characters (``/``) in the object name.

.. code:: php

    $localFileName  = '/path/to/local/php-elephant.jpg';
    $remoteFileName = 'languages/php/elephant.jpg';

    $fileData = fopen($localFileName, 'r');
    $container->uploadObject($remoteFileName, $fileData);

[ `Get the executable PHP script for this
example </samples/ObjectStore/pseudo-hierarchical-folders.php>`__ ]

In the example above, an image file from the local filesystem
(``/path/to/local/php-elephant.jpg``) is uploaded to a container in the
Object Store. Within that container, the filename is
``languages/php/elephant.jpg``, where ``languages/php/`` is a
pseudo-hierarchical folder hierarchy.

Note that while we call ``fopen`` to open the file resource, we do not
call ``fclose`` at the end. The file resource is automatically closed
inside the ``uploadObject`` call.

Upload Multiple Objects
^^^^^^^^^^^^^^^^^^^^^^^

You can upload more than one object at a time to a container.

.. code:: php

    $objects = array(
        array(
            'name' => 'php-elephant.jpg',
            'path'   => '/path/to/local/php-elephant.jpg'
        ),
        array(
            'name' => 'python-snake.jpg',
            'path'   => '/path/to/local/python-snake.jpg'
        ),
        a
    );

    $container->uploadObjects($objects);

[ `Get the executable PHP script for this
example </samples/ObjectStore/upload-multiple-objects.php>`__ ]

In the above example, the contents of two files present on the local
filesystem are uploaded as objects to the container referenced by
``$container``.

Instead of specifying the ``path`` key in an element of the ``$objects``
array, you can specify a ``body`` key whose value is a string or a
stream representation.

Finally, you can pass headers as the second parameter to the
``uploadObjects`` method. These headers will be applied to every object
that is uploaded.

::

    $metadata = array('author' => 'Jane Doe');

    $customHeaders = array();
    $metadataHeaders = DataObject::stockHeaders($metadata);
    $allHeaders = $customHeaders + $metadataHeaders;

    $container->uploadObjects($objects, $allHeaders);

[ `Get the executable PHP script for this
example </samples/ObjectStore/upload-multiple-objects-with-metadata.php>`__
]

In the example above, every object referenced within the ``$objects``
array will be uploaded with the same metadata.

Large Objects
~~~~~~~~~~~~~

If you want to upload objects larger than 5GB in size, you must use a
different upload process.

.. code:: php

    $options = array(
        'name' => 'san_diego_vacation_video.mp4',
        'path'   => '/path/to/local/videos/san_diego_vacation.mp4'
    );
    $objectTransfer = $container->setupObjectTransfer($options);
    $objectTransfer->upload();

[ `Get the executable PHP script for this
example </samples/ObjectStore/upload-large-object.php>`__ ]

The process shown above will automatically partition your large object
into small chunks and upload them concurrently to the container
represented by ``$container``.

You can tune the parameters of this process by specifying additional
options in the ``$options`` array. Here is a complete listing of keys
that can be specified in the ``$options`` array:

+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| Key name          | Description                                                                                                                                                                                                                                                                                                    | Data Type                                       | Required?                                       | Default Value                                    | Example                                            |
+===================+================================================================================================================================================================================================================================================================================================================+=================================================+=================================================+==================================================+====================================================+
| ``name``          | Name of large object in container                                                                                                                                                                                                                                                                              | String                                          | Yes                                             | -                                                | ``san_diego_vacation_video.mp4``                   |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| ``path``          | Path to file containing object data on local filesystem                                                                                                                                                                                                                                                        | String                                          | One of ``path`` or ``body`` must be specified   | -                                                | ``/path/to/local/videos/san_diego_vacation.mp4``   |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| ``body``          | String or stream representation of object data                                                                                                                                                                                                                                                                 | String \| Stream                                | One of ``path`` or ``body`` must be specified   | -                                                | ``... lots of data ...``                           |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| ``metadata``      | Metadata for the object                                                                                                                                                                                                                                                                                        | Associative array of metadata key-value pairs   | No                                              | ``array()``                                      | ``array( "Author" => "Jane Doe" )``                |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| ``partSize``      | The size, in bytes, of each chunk that the large object is partitioned into prior to uploading                                                                                                                                                                                                                 | Integer                                         | No                                              | ``1073741824`` (1GB)                             | ``52428800`` (50MB)                                |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| ``concurrency``   | The number of concurrent transfers to execute as part of the upload                                                                                                                                                                                                                                            | Integer                                         | No                                              | ``1`` (no concurrency; upload chunks serially)   | ``10``                                             |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+
| ``progress``      | A `callable function or method <http://us1.php.net/manual/en/language.types.callable.php>`__ which is called to report progress of the the upload. See ```CURLOPT_PROGRESSFUNCTION`` documentation <http://us2.php.net/curl_setopt>`__ for details on parameters passed to this callable function or method.   | String (callable function or method name)       | No                                              | None                                             | ``reportProgress``                                 |
+-------------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------+-------------------------------------------------+--------------------------------------------------+----------------------------------------------------+

Auto-extract Archive Files
~~~~~~~~~~~~~~~~~~~~~~~~~~

You can upload a tar archive file and have the Object Store service
automatically extract it into a container.

.. code:: php

    use OpenCloud\ObjectStore\Constants\UrlType;

    $localArchiveFileName  = '/path/to/local/image_files.tar.gz';
    $remotePath = 'images/';

    $fileData = fopen($localArchiveFileName, 'r');
    $objectStoreService->bulkExtract($remotePath, $fileData, UrlType::TAR_GZ);

[ `Get the executable PHP script for this
example </samples/ObjectStore/auto-extract-archive-files.php>`__ ]

In the above example, a local archive file named ``image_files.tar.gz``
is uploaded to an Object Store container named ``images`` (defined by
the ``$remotePath`` variable).

Note that while we call ``fopen`` to open a file resource, we do not
call ``fclose`` at the end. The file resource is automatically closed
inside the ``bulkExtract`` call.

The third parameter to ``bulkExtract`` is the type of the archive file
being uploaded. The acceptable values for this are:

-  ``UrlType::TAR`` for tar archive files, *or*,
-  ``UrlType:TAR_GZ`` for tar archive files that are compressed with
   gzip, *or*
-  ``UrlType::TAR_BZ`` for tar archive file that are compressed with
   bzip

Note that the value of ``$remotePath`` could have been a
(pseudo-hierarchical folder)[#pseudo-hierarchical-folders] such as
``images/blog`` as well.

List Objects in a Container
~~~~~~~~~~~~~~~~~~~~~~~~~~~

You can list all the objects stored in a container. An instance of
``OpenCloud\Common\Collection\PaginatedIterator`` is returned.

.. code:: php

    $objects = $container->objectList();
    foreach ($objects as $object) {
        /** @var $object OpenCloud\ObjectStore\Resource\DataObject  **/
    }

[ `Get the executable PHP script for this
example </samples/ObjectStore/list-objects.php>`__ ]

You can list only those objects in the container whose names start with
a certain prefix.

.. code:: php

    $options = array(
        'prefix' => 'php'
    );

    $objects = $container->objectList($options);

[ `Get the executable PHP script for this
example </samples/ObjectStore/list-objects-with-prefix.php>`__ ]

In general, the ``objectList()`` method described above takes an
optional parameter (``$options`` in the example above). This parameter
is an associative array of various options. Here is a complete listing
of keys that can be specified in the ``$options`` array:

+------------------+-----------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------+
| Key name         | Description                                                                 | Data Type   | Required?   | Default Value   | Example                 |
+==================+=============================================================================+=============+=============+=================+=========================+
| ``prefix``       | Given a string x, limits the results to object names beginning with x.      | String      | No          |                 | ``php``                 |
+------------------+-----------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------+
| ``limit``        | Given an integer n, limits the number of results to at most n values.       | Integer     | No          |                 | 10                      |
+------------------+-----------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------+
| ``marker``       | Given a string x, returns object names greater than the specified marker.   | String      | No          |                 | ``php-elephant.jpg``    |
+------------------+-----------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------+
| ``end_marker``   | Given a string x, returns object names less than the specified marker.      | String      | No          |                 | ``python-snakes.jpg``   |
+------------------+-----------------------------------------------------------------------------+-------------+-------------+-----------------+-------------------------+

Retrieve Object
~~~~~~~~~~~~~~~

You can retrieve an object and its metadata, given the object's
container and name.

.. code:: php

    $objectName = 'php-elephant.jpg';
    $object = $container->getObject($objectName);

    /** @var $object OpenCloud\ObjectStore\Resource\DataObject **/

    $objectContent = $object->getContent();

    /** @var $objectContent Guzzle\Http\EntityBody **/

    // Write object content to file on local filesystem.
    $objectContent->rewind();
    $stream = $objectContent->getStream();
    $localFilename = tempnam("/tmp", 'php-opencloud-');
    file_put_contents($localFilename, $stream);

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-object.php>`__ ]

In the example above, ``$object`` is the object named
``php-elephant.jpg`` in the container represented by ``$container``.
Further, ``$objectContent`` represents the contents of the object. It is
of type
```Guzzle\Http\EntityBody`` <http://api.guzzlephp.org/class-Guzzle.Http.EntityBody.html>`__.

Retrieve Object Metadata
~~~~~~~~~~~~~~~~~~~~~~~~

You can retrieve just an object's metadata without retrieving its
contents.

.. code:: php

    $objectName = 'php-elephant.jpg';
    $object = $container->getPartialObject($objectName);
    $objectMetadata = $object->getMetadata();

    /** @var $objectMetadata \OpenCloud\Common\Metadata **/

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-object-metadata.php>`__ ]

In the example above, while ``$object`` is an instance of
``OpenCloud\ObjectStore\Resource\DataObject``, that instance is only
partially populated. Specifically, only properties of the instance
relating to object metadata are populated.

Temporary URLs
~~~~~~~~~~~~~~

The Temporary URL feature allows you to create limited-time Internet
addresses that allow you to grant limited access to your Object Store
account. Using this feature, you can allow others to retrieve or place
objects in your Object Store account for a specified amount of time.
Access to the temporary URL is independent of whether or not your
account is `CDN-enabled <#cdn-containers>`__. Even if you do not
CDN-enable a container, you can still grant temporary public access
through a temporary URL.

First, you must set the temporary URL secret on your account. This is a
one-time operation; you only need to perform it the very first time you
wish to use the temporary URLs feature.

.. code:: php

    $account->setTempUrlSecret();

[ `Get the executable PHP script for this
example </samples/ObjectStore/set-account-temp-url-secret.php>`__ ]

Note that this operation is carried out on ``$account``, which is an
instance of ``OpenCloud\ObjectStore\Resource\Account``, a class
representing `your object store account <#accounts>`__.

The above operation will generate a random secret and set it on your
account. Instead of a random secret, if you wish to provide a secret,
you can supply it as a parameter to the ``setTempUrlSecret`` method.

.. code:: php

    $account->setTempUrlSecret('<SOME SECRET STRING>');

[ `Get the executable PHP script for this
example </samples/ObjectStore/set-account-temp-url-secret-specified.php>`__
]

Once a temporary URL secret has been set on your account, you can
generate a temporary URL for any object in your Object Store.

.. code:: php

    $expirationTimeInSeconds = 3600; // one hour from now
    $httpMethodAllowed = 'GET';
    $tempUrl = $object->getTemporaryUrl($expirationTimeInSeconds, $httpMethodAllowed);

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-object-temporary-url.php>`__ ]

In the example above, a temporary URL for the object is generated. This
temporary URL will provide public access to the object for an hour (3600
seconds), as specified by the ``$expirationTimeInSeconds`` variable.
Further, only GET HTTP methods will be allowed on this URL, as specified
by the ``$httpMethodAllowed`` variable. The other value allowed for the
``$httpMethodAllowed`` variable would be ``PUT``.

You can also retrieve the temporary URL secret that has been set on your
account.

.. code:: php

    $tempUrlSecret = $account->getTempUrlSecret();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-account-temp-url-secret.php>`__ ]

Update Object
~~~~~~~~~~~~~

You can update an object's contents (as opposed to `updating its
metadata <#update-object-metadata>`__) by simply re-\ `uploading the
object <#upload-object>`__ to its container using the same object name
as before.

Update Object Metadata
~~~~~~~~~~~~~~~~~~~~~~

You can update an object's metadata after it has been uploaded to a
container.

.. code:: php

    $object->saveMetadata(array(
        'author' => 'John Doe'
    ));

[ `Get the executable PHP script for this
example </samples/ObjectStore/update-object-metadata.php>`__ ]

Copy Object
~~~~~~~~~~~

You can copy an object from one container to another, provided the
destination container already exists.

.. code:: php

    $object->copy('logos_copy/php.jpg');

[ `Get the executable PHP script for this
example </samples/ObjectStore/copy-object.php>`__ ]

In the example above, both the name of the destination container
(``logos_copy``)and the name of the destination object (``php.jpg``)
have to be specified, separated by a ``/``.

Delete Object
~~~~~~~~~~~~~

When you no longer need an object, you can delete it.

.. code:: php

    $object->delete();

[ `Get the executable PHP script for this
example </samples/ObjectStore/delete-object.php>`__ ]

Bulk Delete
~~~~~~~~~~~

While you can delete individual objects as shown above, you can also
delete objects and empty containers in bulk.

.. code:: php

    $objectStoreService->bulkDelete(array(
        'logos/php-elephant.png',
        'logos/python-snakes.png',
        'some_empty_container'
    ));

[ `Get the executable PHP script for this
example </samples/ObjectStore/bulk-delete.php>`__ ]

In the example above, two objects (``some_container/object_a.png``,
``some_other_container/object_z.png``) and one empty container
(``some_empty_container``) are all being deleted in bulk via a single
command.

CDN Containers
--------------

Note: The functionality described in this section is available only on
the Rackspace cloud. It will not work as described when working with a
vanilla OpenStack cloud.

Any container can be converted to a CDN-enabled container. When this is
done, the objects within the container can be accessed from anywhere on
the Internet via a URL.

Enable CDN Container
~~~~~~~~~~~~~~~~~~~~

To take advantage of CDN capabilities for a container and its objects,
you must CDN-enable that container.

.. code:: php

    $container->enableCdn();

[ `Get the executable PHP script for this
example </samples/ObjectStore/enable-container-cdn.php>`__ ]

Public URLs
~~~~~~~~~~~

Once you have CDN-enabled a container, you can retrieve a
publicly-accessible URL for any of its objects. There are four types of
publicly-accessible URLs for each object. Each type of URL is meant for
a different purpose. The sections below describe each of these URL types
and how to retrieve them.

HTTP URL
^^^^^^^^

You can use this type of URL to access the object over HTTP.

::

    $httpUrl = $object->getPublicUrl();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-cdn-object-http-url.php>`__ ]

Secure HTTP URL
^^^^^^^^^^^^^^^

You can use this type of URL to access the object over HTTP + TLS/SSL.

::

    use OpenCloud\ObjectStore\Constants\UrlType;

    $httpsUrl = $object->getPublicUrl(UrlType::SSL);

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-cdn-object-https-url.php>`__ ]

Streaming URL
^^^^^^^^^^^^^

You can use this type of URL to stream a video or audio object using
Adobe's HTTP Dynamic Streaming.

::

    use OpenCloud\ObjectStore\Constants\UrlType;

    $streamingUrl = $object->getPublicUrl(UrlType::STREAMING);

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-cdn-object-streaming-url.php>`__ ]

IOS Streaming URL
^^^^^^^^^^^^^^^^^

You can use this type of URL to stream an audio or video object to an
iOS device.

::

    use OpenCloud\ObjectStore\Constants\UrlType;

    $iosStreamingUrl = $object->getPublicUrl(UrlType::IOS_STREAMING);

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-cdn-object-ios-streaming-url.php>`__ ]

Update CDN Container TTL
~~~~~~~~~~~~~~~~~~~~~~~~

You can update the TTL of a CDN-enabled container.

.. code:: php

    $cdnContainer = $container->getCdn();
    $cdnContainer->setTtl(<NEW TTL, IN SECONDS>);

[ `Get the executable PHP script for this
example </samples/ObjectStore/set-cdn-container-ttl.php>`__ ]

Disable CDN Container
~~~~~~~~~~~~~~~~~~~~~

If you no longer need CDN capabilities for a container, you can disable
them.

.. code:: php

    $container->disableCdn();

[ `Get the executable PHP script for this
example </samples/ObjectStore/disable-container-cdn.php>`__ ]

Accounts
--------

An **account** defines a namespace for **containers**. An account can
have zero or more containers in it.

Retrieve Account
~~~~~~~~~~~~~~~~

You must retrieve the account before performing any operations on it.

.. code:: php

    $account = $objectStoreService->getAccount();

Get Container Count
~~~~~~~~~~~~~~~~~~~

You can quickly find out how many containers are in your account.

.. code:: php

    $accountContainerCount = $account->getContainerCount();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-account-container-count.php>`__ ]

Get Object Count
~~~~~~~~~~~~~~~~

You can quickly find out how many objects are in your account.

.. code:: php

    $accountObjectCount = $account->getObjectCount();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-account-object-count.php>`__ ]

In the example above, ``$accountObjectCount`` will contain the number of
objects in the account represented by ``$account``.

Get Bytes Used
~~~~~~~~~~~~~~

You can quickly find out the space used by your account, in bytes.

.. code:: php

    $accountSizeInBytes = $account->getBytesUsed();

[ `Get the executable PHP script for this
example </samples/ObjectStore/get-account-bytes-used.php>`__ ]

In the example above, ``$accountSizeInBytes`` will contain the space
used, in bytes, by the account represented by ``$account``.
