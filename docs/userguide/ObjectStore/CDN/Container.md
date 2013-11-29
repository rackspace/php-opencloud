## Setup

```php
use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(

));

$service = $client->objectStoreService('cloudFiles');
```

To access the CDN functionality of a particular container:

```php
$container = $service->getContainer('foo_bar');

$cdn = $container->getCdn();
```

## List CDN-enabled container

To list CDN-only containers, follow the same operation for Storage which lists all containers. The only difference is
which service object you execute the method on.

## CDN-enable and -disable a container

Before a container can be CDN-enabled, it must exist in the storage system. When a container is CDN-enabled, any objects
stored in it are publicly accessible over the Content Delivery Network by combining the container's CDN URL with the
object name.

Any CDN-accessed objects are cached in the CDN for the specified amount of time called the TTL. The default TTL value is
259200 seconds, or 72 hours. Each time the object is accessed after the TTL expires, the CDN refetches and caches the
object for the TTL period.

```php
$container->enableCdn();
$container->disableCdn();
```

## Serving containers through SSL

```php
$cdn->getCdnSslUri();
```

## Streaming CDN-enabled containers

```php
$cdn->getCdnStreamingUri();
```

## iOS streaming

The Cloud Files CDN allows you to stream video to iOS devices without needing to convert your video. Once you
CDN-enable your container, you have the tools necessary for streaming media to multiple devices.

```php
$cdn->getIosStreamingUri();
```

## CDN logging

To enable and disable logging for your CDN:

```php
$cdn->enableCdnLogging();
$cdn->disableCdnLogging();
```