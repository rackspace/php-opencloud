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
* PHP >=5.3.3
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

You can also check out the [Getting Started guide](docs/getting-started.md) for a quick tutorial.

- - -

Alternatively, if you would like to fork or clone the repository into a directory (to work and submit pull requests),
you will need to execute:

```bash
php composer.phar install
```

Instead of the `require` command. You can also specify the `--no-dev` option if you do not want to install phpDocumentor
(which has lots of vendor folders).

Support and Feedback
--------------------
Your feedback is appreciated! If you have specific problems or bugs with this SDK, please file an issue on Github. We
also have a [mailing list](https://groups.google.com/forum/#!forum/php-opencloud), so feel free to join to keep up to
date with all the latest changes and announcements to the library.

For general feedback and support requests, send an email to sdk-support@rackspace.com.

You can also find assistance via IRC on #rackspace at freenode.net.

Contributing
------------
If you'd like to contribute to the project, or require help running the unit/acceptance tests, please view the
[contributing guidelines](https://github.com/rackspace/php-opencloud/blob/master/CONTRIBUTING.md).