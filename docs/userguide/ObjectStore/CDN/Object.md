## Setup

You will need to instantiate the container object and access its CDN functionality as
[documented here](https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/ObjectStore/CDN/Container.md).

## Purge CDN-enabled objects

To remove a CDN object from public access:

```php
$object->purge();
```

You can also provide an optional e-mail address (or comma-delimeted list of e-mails), which the API will send a
confirmation message to once the object has been completely purged:

```php
$object->purge('jamie.hannaford@rackspace.com');
$object->purge('hello@example.com,hallo@example.com');
```

## Hosting websites on CloudFiles

To host a static (i.e. HTML) website on CloudFiles, you must follow these steps:

1. CDN-enable a container
2. Upload all HTML content. You can use nested directory structures.
3. Tell CloudFiles what to use for your default index page like this:

```php
$container->setStaticIndexPage('index.html');
```

4. (Optional) Tell CloudFiles which error page to use by default:

```php
$container->setStaticErrorPage('error.html');
```

Bear in mind that steps 3 & 4 do not upload content, but rather specify a reference to an existing page/CloudFiles object.