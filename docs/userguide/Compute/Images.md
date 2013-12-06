# Compute Images

## Intro

An image is a collection of files for a specific operating system that you use to create or rebuild a server. Rackspace provides prebuilt images. You can also create custom images from servers that you have launched. 

In addition to creating images manually, you can also schedule images of your server automatically. Please consult the [official docs](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/scheduled_images.html) for more information about this extension, including enabling and disabling scheduled images and showing scheduled images.

With standard servers, the entire disk (OS and data) is captured in the image. With Performance servers, only the system disk is captured in the image. The data disks should be backed up using Cloud Backup or Cloud Block Storage to ensure availability in case you need to rebuild or restore a server.

## Setup

You first need to setup a Compute service. For information, please consult the [Compute service](Service.md) documentation.

## List images

```php
$images = $service->imageList();

foreach ($images as $image) {
	
}
```

For more information about [iterators](docs/userguide/Iterators.md), please consult the official documentation.

### Query parameters

You can also refine the list of images returned by providing specific URL parameters:

|Field name|Description|
|---|---|
|server|Filters the list of images by server. Specify the server reference by ID or by full URL.|
|name|Filters the list of images by image name.|
|status|Filters the list of images by status. In-flight images have a status of `SAVING` and the conditional progress element contains a value from 0 to 100, which indicates the percentage completion. For a full list, please consult the `OpenCloud\Compute\Constants\ImageState` class. Images with an `ACTIVE` status are available for use.|
|changes-since|Filters the list of images to those that have changed since the changes-since time. See the [official docs](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/ChangesSince.html) for more information.|
|marker|The ID of the last item in the previous list. See the [official docs](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Paginated_Collections-d1e664.html) for more information.|
|limit|Sets the page size. See the [official docs](http://docs.rackspace.com/servers/api/v2/cs-devguide/content/Paginated_Collections-d1e664.html) for more information.|
|type|Filters base Rackspace images or any custom server images that you have created. Can either be `BASE` or `SNAPSHOT`.|

### Example

You can return more information about each image by setting the `$details` argument to `true`. The second argument can be an array of query parameters:

```php
use OpenCloud\Compute\Constants\ImageState;

$list = $service->imageList(true, array(
	'server' => 'fooBar',
    'status' => ImageState::ACTIVE
));
```

## Get an image

```php
$imageId = '3afe97b2-26dc-49c5-a2cc-a2fc8d80c001';
$image = $service->image($imageId);
```

## Delete an image

```php
$image->delete();
```