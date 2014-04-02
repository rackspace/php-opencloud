# Object Store

**Object Store** is an object-based storage system that stores content and metadata as objects in a cloud.

Specifically, a cloud is made up of one or more regions. Each region can have several **containers**, created by a user. Each container can container several **objects** (sometimes referred to as files), uploaded by the user.

## Getting started

### 1. Instantiate an OpenStack or Rackspace client.

Choose one of the following two options:

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

### 2. Obtain an Object Store service object from the client.
```php
$objectStoreService = $client->objectStoreService(null,'DFW');
```

In the example above, you are connecting to the ``DFW`` region of the cloud. Any containers and objects created with this `$objectStoreService` instance will be stored in that cloud region.

### 2. Create a container for your objects (also referred to as files).

```php
$container = $objectStoreService->createContainer('blog_images');
```

### 3. Upload an object to the container.

```php
$localFileName  = '/path/to/local/image_file.png';
$remoteFileName = 'blog_post_15349_image_2.png';

$fileData = fopen($localFileName, 'r');
$container->uploadObject($remoteFileName, $fileData);
```
[See sample code as standalone script](/../../samples/OpenCloud/ObjectStore)

## Next steps

There is a lot more you can do with containers and objects. See 
the [complete user guide to the Object Store service](USERGUIDE.md).