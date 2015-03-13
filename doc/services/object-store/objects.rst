Objects
=======

Setup
-----

In order to interact with this feature, you must first retrieve a particular
container using its unique name:

.. code-block:: php

  $container = $service->getContainer('{containerName}');


Create an object
----------------

There are three ways to upload a new file, each of which has different
business needs.

.. note::

  Unlike previous versions, you do not need to manually specify your object's
  content type. The API will do this for you.

.. note::

  When working with names that contain non-standard alphanumerical characters
  (such as spaces or non-English characters), you must ensure they are encoded
  with `urlencode <http://php.net/urlencode>`_ before passing them in.

Upload a single file (under 5GB)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

The simplest way to upload a local object, without additional metadata, is by
its path:

.. code-block:: php

  $container->uploadObject('example.txt', fopen('/path/to/file.txt', 'r+'));


The resource handle will be automatically closed by Guzzle in its destructor,
so there is no need to execute ``fclose``.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/upload-object.php>`_


Upload a single file (under 5GB) with metadata
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Although the previous section handles most use cases, there are times when you
want greater control over what is being uploaded. For example, you might want
to control the object's metadata, or supply additional HTTP headers to coerce
browsers to handle the download a certain way. To add metadata to a new object:

.. code-block:: php

  use OpenCloud\ObjectStore\Resource\DataObject;

  // specify optional metadata
  $metadata = array(
      'Author' => 'Camera Obscura',
      'Origin' => 'Glasgow',
  );

  // specify optional HTTP headers
  $httpHeaders = array(
      'Content-Type' => 'application/json',
  );

  // merge the two
  $allHeaders = array_merge(DataObject::stockHeaders($metadata), $httpHeaders);

  // upload as usual
  $container->uploadObject('example.txt', fopen('/path/to/file.txt', 'r+'), $allHeaders);


As you will notice, the first argument to ``uploadObject`` is the remote object
name, i.e. the name it will be uploaded as. The second argument is either a
file handle resource, or a string representation of object content (a temporary
resource will be created in memory), and the third is an array of additional
headers.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/upload-object-with-metadata.php>`_


Batch upload multiple files (each under 5GB)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

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

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/upload-multiple-objects-with-metadata.php>`_


Upload large files (over 5GB)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

For files over 5GB, you will need to use the
``OpenCloud\ObjectStore\Upload\TransferBuilder`` factory to build and execute your
transfer. For your convenience, the Container resource object contains a simple
method to do this heavy lifting for you:

.. code-block:: php

  $transfer = $container->setupObjectTransfer(array(
      'name'        => 'video.mov',
      'path'        => '/home/user/video.mov',
      'metadata'    => array('Author' => 'Jamie'),
      'concurrency' => 4,
      'partSize'    => 1.5 * Size::GB
  ));

  $transfer->upload();


You can specify how many concurrent cURL connections are used to upload
parts of your file. The file is fragmented into chunks, each of which is
uploaded individually as a separate file (the filename of each part will
indicate that it's a segment rather than the full file). After all parts
are uploaded, a manifestfile is uploaded. When the end-user accesses the 5GB
by its true filename, it actually references the manifest file which
concatenates each segment into a streaming download.

In Swift terminology, the name for this process is *Dynamic Large Object (DLO)*.
To find out more details, please consult the `official documentation
<http://docs.rackspace.com/files/api/v1/cf-devguide/content/Large_Object_Creation-d1e2019.html>`_.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/upload-large-object.php>`_


List objects in a container
---------------------------

To return a list of objects:

.. code-block:: php

  $files = $container->objectList();

  foreach ($files as $file) {
      /** @var $file OpenCloud\ObjectStore\Resource\DataObject */
  }

By default, 10,000 objects are returned as a maximum. To get around
this, you can construct a query which refines your result set. For a
full specification of query parameters relating to collection filtering,
see the `official
docs <http://docs.openstack.org/api/openstack-object-storage/1.0/content/list-objects.html>`__.

.. code-block:: php

  $container->objectList(array('prefix' => 'logFile_'));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/list-objects.php>`_


Get object
----------

To retrieve a specific file from Cloud Files:

.. code-block:: php

  /** @var $file OpenCloud\ObjectStore\Resource\DataObject */
  $file = $container->getObject('summer_vacation.mp4');

Once you have access to this ``OpenCloud\ObjectStore\Resource\DataObject``
object, you can access these attributes:

Get object's parent container
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  /** @param $container OpenCloud\ObjectStore\Resource\Container */
  $container = $object->getContainer();


Get file name
~~~~~~~~~~~~~

.. code-block:: php

  /** @param $container OpenCloud\ObjectStore\Resource\Container */
  $container = $object->getContainer();


Get file size
~~~~~~~~~~~~~

.. code-block:: php

  /** @param $size int */
  $size = $object->getContentLength();


Get content of file
~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  /** @param $content Guzzle\Http\EntityBody */
  $content = $object->getContainer();


Get type of file
~~~~~~~~~~~~~~~~

.. code-block:: php

  /** @param $type string */
  $type = $object->getContentType();


Get file checksum
~~~~~~~~~~~~~~~~~

.. code-block:: php

  /** @param $etag string */
  $etag = $object->getEtag();


Get last modified date of file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: php

  /** @param $lastModified string */
  $lastModified = $object->getLastModified();


Conditional requests
~~~~~~~~~~~~~~~~~~~~

You can also perform conditional requests according to `RFC 2616
specification <http://www.ietf.org/rfc/rfc2616.txt>`__ (§§ 14.24-26).
Supported headers are ``If-Match``, ``If-None-Match``,
``If-Modified-Since`` and ``If-Unmodified-Since``.

So, to retrieve a file's contents only if it's been recently changed

.. code-block:: php

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

.. code-block:: php

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

.. code-block:: php

  $snippet = $container->getObject('output.log', array('range' => 'bytes=-20'));


Update an existing object
-------------------------

.. code-block:: php

  $file->setContent(fopen('/path/to/new/content', 'r+'));
  $file->update();

Bear in mind that updating a file name will result in a new file being
generated (under the new name). You will need to delete the old file.


Copy object to new location
---------------------------

To copy a file to another location, you need to specify a string-based
destination path:

.. code-block:: php

  $object->copy('/container_2/new_object_name');

Where ``container_2`` is the name of the container, and ``new_object_name`` is
the name of the object inside the container that does not exist yet.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/copy-object.php>`_


Get object metadata
-------------------

You can fetch just the object metadata without fetching the full
content:

.. code-block:: php

  $container->getPartialObject('summer_vacation.mp4');


In order to access the metadata on a partial or complete object, use:

.. code-block:: php

  $object->getMetadata();


You can turn a partial object into a full object to get the content
after looking at the metadata:

.. code-block:: php

  $object->refresh();


You can also update to get the latest metadata:

.. code-block:: php

  $object->retrieveMetadata();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/get-object-metadata.php>`_


Update object metadata
----------------------

Similarly, with setting metadata there are two options: you can update
the metadata values of the local object (i.e. no HTTP request) if you
anticipate you'll be executing one soon (an update operation for
example):

.. code-block:: php

  // There's no need to execute a HTTP request, because we'll soon do one anyway for the update operation
  $object->setMetadata(array(
      'Author' => 'Hemingway'
  ));

  // ... code here

  $object->update();

Alternatively, you can update the API straight away - so that everything
is retained:

.. code-block:: php

  $object->saveMetadata(array(
      'Author' => 'Hemingway'
  ));

Please be aware that these methods override and wipe existing values. If
you want to append values to your metadata, use the correct method:

.. code-block:: php

  $metadata = $object->appendToMetadata(array(
    'Author' => 'Hemingway'
  ));

  $object->saveMetadata($metadata);

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/update-object-metadata.php>`_


Extract archive
---------------

CloudFiles provides you the ability to extract uploaded archives to
particular destinations. The archive will be extracted and its contents
will populate the particular area specified. To upload file (which might
represent a directory structure) into a particular container:

.. code-block:: php

  use OpenCloud\ObjectStore\Constants\UrlType;

  $service->bulkExtract('container_1', fopen('/home/jamie/files.tar.gz','r'), UrlType::TAR_GZ);

You can also omit the container name (i.e. provide an empty string as
the first argument). If you do this, the API will create the containers
necessary to house the extracted files - this is done based on the
filenames inside the archive.

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/auto-extract-archive-files.php>`_


Delete object
-------------

.. code-block:: php

  $object->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/delete-object.php>`_


Delete multiple objects
-----------------------

Bulk delete a set of paths:

.. code-block:: php

  $pathsToBeDeleted = array('/container_1/old_file', '/container_2/notes.txt', '/container_1/older_file.log');

  $service->bulkDelete($pathsToBeDeleted);

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/ObjectStore/bulk-delete.php>`_
