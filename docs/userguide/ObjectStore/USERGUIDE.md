# The Complete User Guide to the Object Store Service

**Object Store** is an object-based storage system that stores content and metadata as objects in a cloud.

## Prerequisites

### Client
To use the object store service, you must first instantiate a `OpenStack` or `Rackspace` client object.

* If you are working with a vanilla OpenStack cloud, instantiate an `OpenCloud\OpenStack` client as shown below.

    ```php
    use OpenCloud\OpenStack;

    $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
    ));
    ```

* If you are working with the Rackspace cloud, instantiate a `OpenCloud\Rackspace` client as shown below.

    ```php
    use OpenCloud\Rackspace;

    $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
    ));
    ```
    
### Object Store Service
All operations on the object store are done via an object store service object.

```php
$objectStoreService = $client->objectStoreService(null, 'DFW');
```

In the example above, you are connecting to the ``DFW`` region of the cloud. Any containers and objects created with this `$objectStoreService` instance will be stored in that cloud region.

## Containers
A **container** defines a namespace for **objects**. An object with the same name in two different containers represents two different objects.

For example, you may create a container called `blog_images` to hold all the image files for your blog.

A container may contain zero or more objects in it.

### Create Container
```php
$container = $objectStoreService->createContainer('blog_images');
```

### Get Container Details
You can retrieve a single container's details by using its name. An instance of `OpenCloud\ObjectStore\Resource\Container` is returned.

```php
$container = $objectStoreService->getContainer('blog_images');

/** @var $container OpenCloud\ObjectStore\Resource\Container **/
```

### List Containers
You can retrieve a list of all your containers. An instance of `OpenCloud\Common\Collection\PaginatedIterator`
is returned.

```php
$containers = $objectStoreService->listContainers();
foreach ($containers as $container) {
    /** @var $container OpenCloud\ObjectStore\Resource\Container  **/
}
```

### Set or Update Container Metadata
```php
$container->saveMetadata(array(
    'author' => 'John Doe'
));
```

### Delete Container
When you no longer have a need for the container, you can remove it. 

If the container is empty (that is, it has no objects in it), you can remove it as shown below:

```php
$container->delete();
```

If the container is not empty (that is, it has objects in it), you have two choices in how to remove it:

* [Individually remove each object](#delete-object) in the container, then remove the container itself as shown above, or

* Remove the container and all the objects within it as shown below:

    ```php
    $container->delete(true);
    ```

### Get Object Count

You can quickly find out how many objects are in a container.

```php
$containerObjectCount = $container->getObjectCount();
```

In the example above, `$containerObjectCount` will contain the number of objects in the container represented by `$container`.

### Get Bytes Used

You can quickly find out the space used by a container, in bytes.

```php
$containerSizeInBytes = $container->getBytesUsed();
```

In the example above, `$containerSizeInBytes` will contain the space used, in bytes, by the container represented by `$container`.

### Container Synchronization

[TODO]

### Container Quotas

[TODO]

## Objects

An **object** (sometimes referred to as a file) is the unit of storage in an Object Store.  An object is a combination of content (data) and metadata.

For example, you may upload an object named `blog_post_15349_image_2.png`, a PNG image file, to the `blog_images` container. Further, you may assign metadata to this object to indiciate that the author of this object was someone named Jane Doe.

### Upload Object

Once you have created a container, you can upload objects to it.

```php
$localFileName  = '/path/to/local/image_file.png';
$remoteFileName = 'blog_post_15349_image_2.png';

$fileData = fopen($localFileName, 'r');
$container->uploadObject($remoteFileName, $fileData);
```
In the example above, an image file from the local filesystem (`path/to/local/image_file.png`) is uploaded to a container in the Object Store. Note that the uploaded object is given a new name (`blog_post_15349_image_2.png`).

It is also possible to upload an object and associate metadata with it.

```php
$localFileName  = '/path/to/local/image_file.png';
$remoteFileName = 'blog_post_15349_image_2.png';
$metadata = array('author' => 'Jane Doe');

$customHeaders = array();
$metadataHeaders = DataObject::stockHeaders($metadata);
$allHeaders = $customHeaders + $metadataHeaders; 

$fileData = fopen($localFileName, 'r');
$container->uploadObject($remoteFileName, $fileData, $allHeaders);
```

#### Pseudo-hierarchical Folders
Although you cannot nest directories in an Object Store, you can simulate a hierarchical structure within a single container by adding forward slash characters (`/`) in the object name.

```php
$localFileName  = '/path/to/local/image_file.png';
$remoteFileName = '15349/2/orig.png';

$fileData = fopen($localFileName, 'r');
$container->uploadObject($remoteFileName, $fileData);
```

In the example above, an image file from the local filesystem (`path/to/local/image_file.png`) is uploaded to a container in the Object Store. Within that container, the filename is `15349/2/orig.png`, where `15349/2/` is a pseudo-hierarchicial folder hierarchy.

#### Upload Multiple Objects
You can upload more than one object at a time to a container.

```php
$objects = array(
    array(
        'name' => 'image_1.png',
        'path'   => '/path/to/local/image_file_1.png'
    ),
    array(
        'name' => 'image_2.png',
        'path'   => '/path/to/local/image_file_2.png'
    ),
    array(
        'name' => 'image_3.png',
        'path'   => '/path/to/local/image_file_3.png'
    )
);

$container->uploadObjects($objects);
```

In the above example, the contents of three files present on the local filesystem are uploaded as objects to the container referenced by `$container`.

Instead of specifying the `path` key in an element of the `$objects` array, you can specify a `body` key whose value is a string or a stream representation.

Finally, you can pass headers as the second parameter to the `uploadObjects` method. These headers will be applied to every object that is uploaded.

```
$metadata = array('author' => 'Jane Doe');

$customHeaders = array();
$metadataHeaders = DataObject::stockHeaders($metadata);
$allHeaders = $customHeaders + $metadataHeaders; 

$container->uploadObjects($objects, $allHeaders);
```

In the example above, every object referenced within the `$objects` array will be uploaded with the same metadata.


### Large Objects

[TODO]

#### Segment Objects

[TODO]

#### Manifest Objects

[TODO]

##### Static Large Objects

[TODO]

##### Dynamic Large Objects

[TODO]

### Object Versioning

[TODO]

### Auto-extract Archive Files

You can upload a tar archive file and have the Object Store service automatically extract it into a container. 

```php
use OpenCloud\ObjectStore\Constants\UrlType;

$localArchiveFileName  = '/path/to/local/image_files.tar.gz';
$remotePath = 'images/';

$fileData = fopen($localArchiveFileName, 'r');
$objectStoreService->bulkExtract($remotePath, $fileData, UrlType::TAR_GZ);
```

In the above example, a local archive file named `image_files.tar.gz` is uploaded to an Object Store container named `images` (defined by the `$remotePath` variable).

The third parameter to `bulkExtract` is the type of the archive file being uploaded. The acceptable values for this are:

* `UrlType::TAR` for tar archive files, *or*,
* `UrlType:TAR_GZ` for tar archive files that are compressed with gzip, *or*
* `UrlType::TAR_BZ` for tar archive file that are compressed with bzip
 
Note that the value of `$remotePath` could have been a (pseudo-hierarchical folder)[#psuedo-hierarchical-folders] such as `images/blog` as well. 

### List Objects in a Container
You can list all the objects stored in a container. An instance of `OpenCloud\Common\Collection\PaginatedIterator` is returned.


```php
$objects = $container->objectList();
foreach ($objects as $object) {
    /** @var $object OpenCloud\ObjectStore\Resource\DataObject  **/	}
```

### Retrieve Object
You can retrieve an object and its metadata, given the object's container and name.

```php
$objectName = 'blog_post_15349_image_2.png';
$object = $container->getObject($objectName);

/** @var $object OpenCloud\ObjectStore\Resource\DataObject **/
```

### Retrieve Object Metadata
You can retrieve just an object's metadata without retrieving its contents.

```php
$objectName = 'blog_post_15349_image_2.png';
$object = $container->getPartialObject($objectName);

/** @var $object OpenCloud\ObjectStore\Resource\DataObject **/
```

In the example above, while `$object` is an instance of `OpenCloud\ObjectStore\Resource\DataObject`, that instance is only partially populated. Specifically, only properties of the instance relating to object metadata are populated.

### Temporary URLs

[TODO]

### Update Object

You can update an object's contents (as opposed to [updating its metadata](#update-object-metadata)) by simply re-[uploading the object](#upload-object) to its container using the same object name as before.

### Update Object Metadata

You can update an object's metadata after it has been uploaded to a container.

```php
$object->saveMetadata(array(
    'author' => 'John Doe'
));
```

### Assign CORS Headers to Objects

[TODO]

### Use Content-Encoding Metadata

[TODO]

### Use Content-Disposition Metadata

[TODO]

### Copy Object

You can copy an object from one container to another.

```php
$object->copy('some_other_container/object_new_name.png');
```

In the example above, both the name of the destination container (`some_other_container`)and the name of the destination object (`object_new_name.png`) have to be specified, separated by a `/`.

### Delete Object

When you no longer need an object, you can delete it.

```php
$object->delete();
```

### Bulk Delete

While you can delete individual objects as shown above, you can also delete objects and empty containers in bulk.

```php
$objectStoreService->bulkDelete(array(
    'some_container/object_a.png',
    'some_other_container/object_z.png',
    'some_empty_container'
));
```

In the example above, two objects (`some_container/object_a.png`, `some_other_container/object_z.png`) and one empty container (`some_empty_container`) are all being deleted in bulk via a single command.

### Schedule Objects for Deletion

[TODO]

#### Delete Object at Specified Time

[TODO]

#### Delete Object after Specified Interval

[TODO]

## CDN Containers

*Note: The functionality described in this section is  available **only on the Rackspace cloud**. It will not work as described when working with a vanilla OpenStack cloud.*

Any container can be converted to a CDN-enabled container. When this is done, the objects within the container can be accessed from anywhere on the Internet via a URL.

### Enable CDN Container

To take advantage of CDN capabilities for a container and its objects, you must CDN-enable that container.

```php
$container->enableCdn();
```

### Public URLs

Once you have CDN-enabled a container, you can retrieve a publicly-accessible URL for any of its objects. There are four types of publicly-accessible URLs for each object. Each type of URL is meant for a different purpose. The sections below describe each of these URL types and how to retrieve them.

#### HTTP URL
You can use this type of URL to access the object over HTTP.

```
$httpUrl = $object->getPublicUrl();
```

#### Secure HTTP URL
You can use this type of URL to access the object over HTTP + TLS/SSL.

```
use OpenCloud\ObjectStore\Constants\UrlType;

$httpsUrl = $object->getPublicUrl(UrlType::SSL);
```

#### Streaming URL
You can use this type of URL to stream a video or audio object using Adobe's HTTP Dynamic Streaming.

```
use OpenCloud\ObjectStore\Constants\UrlType;

$httpsUrl = $object->getPublicUrl(UrlType::STREAMING);
```

#### IOS Streaming URL

You can use this type of URL to stream an audio or video object to an iOS device.

```
use OpenCloud\ObjectStore\Constants\UrlType;

$httpsUrl = $object->getPublicUrl(UrlType::IOS_STREAMING);
```

### Disable CDN Container

If you no longer need CDN capabilities for a container, you can disable them.

```php
$container->disableCdn();
```

## Accounts
An **account** defines a namespace for **containers**. An account can have zero or more containers in it.

### Retrieve Account
You must retrieve the account before performing any operations on it.

```php
$account = $objectStoreService->getAccount();
```

### Get Container Count

You can quickly find out how many containers are in your account.

```php
$accountContainerCount = $account->getContainerCount();
```

### Get Object Count

You can quickly find out how many objects are in your account.

```php
$accountObjectCount = $account->getObjectCount();
```

In the example above, `$accountObjectCount` will contain the number of objects in the account represented by `$account`.

### Get Bytes Used

You can quickly find out the space used by your account, in bytes.

```php
$accountSizeInBytes = $account->getBytesUsed();
```

In the example above, `$accountSizeInBytes` will contain the space used, in bytes, by the account represented by `$account`.
