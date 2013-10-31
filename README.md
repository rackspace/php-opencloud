**php-opencloud**
=============
PHP SDK for OpenStack/Rackspace APIs

[![Latest Stable Version](https://poser.pugx.org/rackspace/php-opencloud/v/stable.png)](https://packagist.org/packages/rackspace/php-opencloud) [![Travis CI](https://secure.travis-ci.org/rackspace/php-opencloud.png)](https://travis-ci.org/rackspace/php-opencloud) [![Total Downloads](https://poser.pugx.org/rackspace/php-opencloud/downloads.png)](https://packagist.org/packages/rackspace/php-opencloud)

For SDKs in different languages, see http://developer.rackspace.com.

The PHP SDK should work with most OpenStack-based cloud deployments,
though it specifically targets the Rackspace public cloud. In
general, whenever a Rackspace deployment is substantially different
than a pure OpenStack one, a separate Rackspace subclass is provided
so that you can still use the SDK with a pure OpenStack instance
(for example, see the `OpenStack` class (for OpenStack) and the
`Rackspace` subclass).

Requirements
------------
* PHP >= 5.3.3
* cURL extension for PHP

Installation
------------
You must install this library through Composer:

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php

# Require php-opencloud as a dependency
php composer.phar require rackspace/php-opencloud:dev-master
```

Once you have installed the library, you will need to load Composer's autoloader (which registers all the required
namespaces):

```php
require 'vendor/autoload.php';
```

And you're ready to go!

Support and Feedback
--------------------
Your feedback is appreciated! If you have specific problems or bugs with this SDK, please file an issue on Github.

For general feedback and support requests, send an email to sdk-support@rackspace.com.

You can also find assistance via IRC on #rackspace at freenode.net.

Contributing
------------
If you'd like to contribute, see the
[Contributing guide](https://github.com/rackspace/php-opencloud/blob/master/CONTRIBUTING.md) and the
[TODO list](https://github.com/rackspace/php-opencloud/blob/master/TODO.md).

Further Reading
---------------
The file
[docs/quickref.md](https://github.com/rackspace/php-opencloud/blob/master/docs/quickref.md)
contains a Quick Reference
guide to the
**php-opencloud** library.

The source for the "Getting Started with
**php-opencloud**" document (the user guide) starts in
[docs/userguide/index.md](https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/index.md).

There is a complete (auto-generated) API reference manual in the
docs/api directory. Start with docs/api/index.html.

See the [HOWTO.md](https://github.com/rackspace/php-opencloud/blob/master/HOWTO.md) file for instructions on
regenerating the documentation and running tests.

See the [smoketest.php](https://github.com/rackspace/php-opencloud/blob/master/smoketest.php) file for some
simple, working examples. This is a test we run before builds to ensure that all
the core functionality is still working after code changes.

The [samples/](https://github.com/rackspace/php-opencloud/tree/master/samples/) directory has a collection
of tested, working sample code. Note that these may create objects in your cloud
for which you could be charged.

