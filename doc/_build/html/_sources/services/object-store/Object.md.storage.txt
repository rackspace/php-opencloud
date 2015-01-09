Setup
-----

Conceptually, a container contains objects (also known as files). In
order to work with objects, you will need to instantiate a container
object first as `documented
here <https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/ObjectStore/Storage/Container.md>`__.

Note on object properties
-------------------------

Please be aware that you cannot directly access the properties of
DataObject anymore, you **must** use appropriate getter/ setter methods:

+----------------------+------------------------+
| Property             | Method                 |
+======================+========================+
| Parent container     | ``getContainer``       |
+----------------------+------------------------+
| Name                 | ``getName``            |
+----------------------+------------------------+
| Body of file         | ``getContent``         |
+----------------------+------------------------+
| Size of file         | ``getContentLength``   |
+----------------------+------------------------+
| Type of file         | ``getContentType``     |
+----------------------+------------------------+
| ETag checksum        | ``getEtag``            |
+----------------------+------------------------+
| Last modified date   | ``getLastModified``    |
+----------------------+------------------------+

Create an object
----------------

There are three ways to upload a new file, each of which has different
business needs.

    **Note:** Unlike previous versions, you do not need to manually
    specify your object's content type. The API will do this for you.

    **Note:** when working with names that contain non-standard
    alphanumerical characters (such as spaces or non-English
    characters), you must ensure they are encoded with
    ```urlencode`` <http://php.net/urlencode>`__ before passing them in

To upload a single/basic file:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    use OpenCloud\ObjectStore\Resource\DataObject;

    $data = fopen('/path/to/sample.mp3', 'r+');

    // alternatively, you can pass in a string as the file contents `$data` argument (instead of a resource)

    $meta = array(
        'Author' => 'Camera Obscura',
        'Origin' => 'Glasgow'
    );

    $metaHeaders = DataObject::stockHeaders($meta);
    $customHeaders = array();
    $allHeaders = $metaHeaders + $customHeaders;

    $container->uploadObject('sample.mp3', $data, $allHeaders);

To upload multiple small-to-mid sized files:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $files = array(
        array(
            'name' => 'apache.log',
            'path' => '/etc/httpd/logs/error_log'
        ),
        array(
            'name' => 'mysql.log',
            'body' => fopen('/tmp/mysql.log', 'r+')
        ),
        array(
            'name' => 'to_do_list.txt',
            'body' => 'PHONE HOME'
        )
    );

    $container->uploadObjects($files);

As you can see, the ``name`` key is required for every file. You must
also specify *either* a path key (to an existing file), or a ``body``.
The ``body`` can either be a PHP resource or a string representation of
the content you want to upload.

To upload large files
~~~~~~~~~~~~~~~~~~~~~

For files over 5GB, you will need to use the
``OpenCloud\ObjectStore\Upload\TransferBuilder`` factory to build your
transfer, upon which you can execute your upload functionality. For your
convenience, the Container resource object contains a simple method to
do this heavy lifting for you:

.. code:: php

    $transfer = $container->setupObjectTransfer(array(
        'name' => 'video.mov',
        'path' => '/home/jamie/video.mov',
        'metadata' => array(
            'Author' => 'Jamie'
        ),
        'concurrency' => 4,
        'partSize'    => 1.5 * Size::GB
    ));

    $transfer->upload();

You can specify how many concurrent cURL connections are used to upload
parts of your file. The file is fragmented into chunks, each of which is
uploaded individually as a separate file (the filename of each part will
indicate that it's a segment rather than the full file). After all parts
are uploaded, a manifest is uploaded. When the end-user accesses the 5GB
by its true filename, it actually references the manifest file which
concatenates each segment into a streaming download.

List objects in a container
---------------------------

To return a list of objects:

.. code:: php

    $files = $container->objectList();

    foreach ($files as $file) {
        // ... do something
    }

By default, 10,000 objects are returned as a maximum. To get around
this, you can construct a query which refines your result set. For a
full specification of query parameters relating to collection filtering,
see the `official
docs <http://docs.openstack.org/api/openstack-object-storage/1.0/content/list-objects.html>`__.

.. code:: php

    $container->objectList(array('prefix' => 'logFile_'));

Get object
----------

To retrieve a specific file from Cloud Files:

.. code:: php

    $file = $container->getObject('summer_vacation.mp4');

Conditional requests
~~~~~~~~~~~~~~~~~~~~

You can also perform conditional requests according to `RFC 2616
specification <http://www.ietf.org/rfc/rfc2616.txt>`__ (§§ 14.24-26).
Supported headers are ``If-Match``, ``If-None-Match``,
``If-Modified-Since`` and ``If-Unmodified-Since``.

So, to retrieve a file's contents only if it's been recently changed

.. code:: php

    $file = $container->getObject('error_log.txt', array(
        'If-Modified-Since' => 'Tue, 15 Nov 1994 08:12:31 GMT'
    ));

    if ($file->getContentLength()) {
        echo 'Has been changed since the above date';
    } else {
        echo 'Has not been changed';
    }

Retrieve a file only if it has NOT been modified (and expect a 412 on
failure):

::

    use Guzzle\Http\Exception\ClientErrorResponseException;

    try {
        $oldImmutableFile = $container->getObject('payroll_2001.xlsx', array(
            'If-Unmodified-Since' => 'Mon, 31 Dec 2001 23:00:00 GMT'
        ));
    } catch (ClientErrorResponseException $e) {
        echo 'This file has been modified...';
    }

Finally, you can specify a range - which will return a subset of bytes
from the file specified. To return the last 20B of a file:

.. code:: php

    $snippet = $container->getObject('output.log', array('range' => 'bytes=-20'));

Update an existing object
-------------------------

Updating content is easy:

.. code:: php

    $file->setContent(fopen('/path/to/new/content', 'r+'));
    $file->update();

Bear in mind that updating a file name will result in a new file being
generated (under the new name). You will need to delete the old file.

Copy object
-----------

To copy a file to another location, you need to specify a string-based
destination path:

.. code:: php

    $object->copy('/container_2/new_object_name');

Delete object
-------------

.. code:: php

    $object->delete();

Get object metadata
-------------------

You can fetch just the object metadata without fetching the full
content:

.. code:: php

    $container->getPartialObject('summer_vacation.mp4');

In order to access the metadata on a partial or complete object, use:

.. code:: php

    $object->getMetadata();

You can turn a partial object into a full object to get the content
after looking at the metadata:

.. code:: php

    $object->refresh();

You can also update to get the latest metadata:

.. code:: php

    $object->retrieveMetadata();

Update object metadata
----------------------

Similarly, with setting metadata there are two options: you can update
the metadata values of the local object (i.e. no HTTP request) if you
anticipate you'll be executing one soon (an update operation for
example):

.. code:: php

    // There's no need to execute a HTTP request, because we'll soon do one anyway for the update operation
    $object->setMetadata(array(
        'Author' => 'Hemingway'
    ));

    // ... code here

    $object->update();

Alternatively, you can update the API straight away - so that everything
is retained:

.. code:: php

    $object->saveMetadata(array(
        'Author' => 'Hemingway'
    ));

Please be aware that these methods override and wipe existing values. If
you want to append values to your metadata, use the correct method:

.. code:: php

    $metadata = $object->appendToMetadata(array(
      'Author' => 'Hemingway'
    ));

    $object->saveMetadata($metadata);

Extract archive
---------------

CloudFiles provides you the ability to extract uploaded archives to
particular destinations. The archive will be extracted and its contents
will populate the particular area specified. To upload file (which might
represent a directory structure) into a particular container:

.. code:: php

    use OpenCloud\ObjectStore\Constants\UrlType;

    $service->bulkExtract('container_1', fopen('/home/jamie/files.tar.gz','r'), UrlType::TAR_GZ);

You can also omit the container name (i.e. provide an empty string as
the first argument). If you do this, the API will create the containers
necessary to house the extracted files - this is done based on the
filenames inside the archive.

Bulk delete
-----------

Bulk delete a set of paths:

.. code:: php

    $pathsToBeDeleted = array('/container_1/old_file', '/container_2/notes.txt', '/container_1/older_file.log');

    $service->bulkDelete($pathsToBeDeleted);

