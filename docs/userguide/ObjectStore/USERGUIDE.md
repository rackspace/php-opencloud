# The Complete User Guide to the Object Store Service

**Object Store** is an object-based storage system that stores content and metadata as objects in a cloud.

Specifically, a cloud is made up of one or more regions. Each region can have several **containers**, created by a user. Each container can container several **objects** (sometimes referred to as files), uploaded by the user. [TODO: Where do **accounts** fit in? Should they be mentioned here?]

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

### Set or Edit Container Metadata
[TODO]

### Delete Container
When you no longer have a need for the container, you can remove it. 

If the container is empty (that is, it has no objects in it), you can remove it as shown below:

```php
$container->delete();
```

If the container is not empty (that is, it has objects in it), you have two choices in how to remove it:

* Individually remove each object in the container [TODO: link to delete object], then remove the container itself as shown above, or

* Remove the container and all the objects within it as shown below:

    ```php
    $container->delete(true);
    ```

## Objects (also referred to as Files)

An **object** is the unit of storage in an Object Store. An object stores data content, such as documents, images, and so on. You can also store custom metadata with an object.

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

### Update Object

You can update an object's contents (as opposed to [updating its metadata](#update-object-metadata)) by simply re-[uploading the object](#upload-object) to its container using the same object name as before.

### Update Object Metadata

You can update an object's metadata after it has been uploaded to a container.

```php
$object->saveMetadata(array(
    'author' => 'John Doe'
));
```

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

## Accounts
[TODO: Redo introduction above to fit in accounts]

## CDN Containers

*Note: The functionality described in this section is  available **only on the Rackspace cloud**. It will not work as described when working with a vanilla OpenStack cloud.*

## Other Features [TODO: Where to put these?]

### Object Versioning

### Large Objects

#### Segment Objects

#### Manifest Objects

##### Static Large Objects

##### Dynamic Large Objects

### Assign CORS Headers to Objects

### Use Content-Encoding Metadata

### Use Content-Disposition Metadata

### Schedule Objects for Deletion

#### Delete Object at Specified Time

#### Delete Object after Specified Interval

### Pseudo-hierarchical Folders

### Auto-extract Archive Files

### Bulk Delete

### Container Synchronization

### Container Quotas

### Temporary URLs
