Getting started with php-opencloud
==================================

The <b>php-opencloud</b> library is a software development kit (SDK) to help
developers using PHP to work with OpenStack&reg; and other open clouds. Since
the library was developed at Rackspace, it works with the Rackspace public
cloud, and should also work with other OpenStack-based clouds with minimal
effort.

The main difference between various cloud providers is in how they handle
authentication. The OpenStack Keystone project defines a standard API for
authenticating users but, because of differences in the underlying
authentication mechanisms used by the various public cloud providers,
the actual credentials used for authentication will probably differ.
See the [Authentication](authentication.md) section for more details on this.

This initial release of the <b>php-opencloud</b> library supports Compute
(OpenStack Nova) and Object Storage (OpenStack Swift) services.
It also supports Rackspace's extensions to these services, such as the
content distribution network (CDN) provided with Rackspace's Cloud Files&trade;
offering, which is built on OpenStack Swift.

## Before you start

PHP 5.3 is required. This library uses PHP namespaces, which were introduced
with version 5.3, so it will not work with earlier versions.

It uses the [CURL library](http://us.php.net/manual/en/book.curl.php)
to communicate over HTTP to the various services.
Since this is included with nearly all PHP installations, that should not be
a problem. However, if your installation does not include CURL,
then you'll need to make sure it is installed.

## Installing the library

In the .ZIP or .tar file in which you received the library, everything under
the `lib/` directory should be installed in a location that is accessible
in your PHP's `include_path` setting. (Or, if you prefer, you can edit
your `php.ini` and change the `include_path` to point to the `lib/` directory,
wherever it is.)

You can also use the full path to the library as shown below.

## Using php-opencloud

There are currently two top-level entry points into the library:

* `openstack.inc` for pure OpenStack-based clouds
* `rackspace.inc` for the Rackspace public cloud

(`rackspace.inc` is a subclass of `OpenStack` with some variation as
to how the authentication is handled, as well as factory methods for
Rackspace-only services such as Cloud Databases&trade;.)

To use the library, you can `include()` or `require()` one of these scripts
in your program, like this:

    <?php
    include('openstack.inc');   // for OpenStack clouds

or

    <?php
    include('rackspace.inc');   // for the Rackspace cloud

If you prefer, you can use the full path to the library file:

    <?php
    include('/full/path/to/your/rackspace.inc');

In this case, you do not need to have the library location in your PHP's `include_path`
setting.

Before you can do anything else, you need to authenticate with
your cloud. See the [Authentication](authentication.md) section
for more details on that.

----

Table of contents
=================

* [Authentication](authentication.md)
* [Working with services](services.md)
* [Factory methods](factories.md)
* [Collections](collections.md)
* [Exceptions and Errors](exceptions.md)
* [Working with object storage](objectstore.md)
* [Working with objects](objects.md)
* [Working with compute](compute.md)
* [Working with servers](servers.md)
* [Working with images](images.md)
* [Working with flavors](flavors.md)
* [About the access IPs](accessip.md)
* [Working with Cloud Databases](dbaas.md)

----

Other Available Documentation
=============================

The *php-opencloud API Reference* is auto-generated documentation on the entire
php-opencloud library. Because it is auto-generated, it is kept in sync with the
actual underlying source code (unlike this document, which must be manually 
edited). 
[@TODO provide links to internal and online versions]

The *php-opencloud Quick Reference* is a simplified reference, providing API 
descriptions and syntax for the core features of the library. It is found in
various formats:

* `docs/quickref.md` The Markdown source
* [@TODO link to online version]
* [@TODO link to downloadable PDF]

A note for developers
=====================

While this library has been developed at
[Rackspace](http://www.rackspace.com),
it is a true open-source initiative. If you would like to add your cloud
provider's support, feel free to submit your changes via our
Github repository:

https://github.com/rackspace/php-opencloud

Likewise, if you find any bugs or problems, please report them via the
Github issue system.
