**php-opencloud**
=============
PHP SDK for OpenStack/Rackspace APIs

See the
[COPYING](https://github.com/rackspace/php-opencloud/blob/master/COPYING)
file for license and copyright information.

The PHP SDK should work with most OpenStack-based cloud deployments,
though it specifically targets the Rackspace public cloud. In
general, whenever a Rackspace deployment is substantially different
than a pure OpenStack one, a separate Rackspace subclass is provided
so that you can still use the SDK with a pure OpenStack instance
(for example, see the `OpenStack` class (for OpenStack) and the
`Rackspace` subclass).

See the [Release Notes](php-opencloud/blob/master/RELEASENOTES.md)
for what has changed in the latest release(s).

The easiest way to download the **php-opencloud** bindings is to use the
GitHub "ZIP" button, above.

Support and Feedback
--------------------
Your feedback is appreciated! If you have specific problems or bugs with the
**php-opencloud**
language bindings, please file an issue via Github.

For general feedback and support requests, send an email to:

sdk-support@rackspace.com

Getting Started with OpenStack/Rackspace
----------------------------------------
To sign up for a Rackspace Cloud account, go to
http://www.rackspace.com/cloud and follow the prompts.

If you are working with an OpenStack deployment, you can find more
information at http://www.openstack.org.

### Requirements

We are not able to test and validate every possible combination of PHP
versions and supporting libraries, but here's our recommended minimum
version list:

* PHP 5.3
* CURL extensions to PHP

### Installation

You can download the latest official release here:

https://github.com/rackspace/php-opencloud/downloads

Move the files in the `lib/` directory to a location in your PHP's
`include_path` or, conversely, set the `include_path` to point to the
location of the `lib/` files. From within a PHP program, for example,
you can use:

    ini_set('include_path', './lib:'.ini_get('include_path'));

This prepends the local `./lib` directory to the existing `include_path`
value.

If you prefer, you can modify the `include_path` setting in your `php.ini`
file (usually found in `/etc/php.ini` or `/usr/local/etc/php.ini`).

If `php.ini` has this line:

    include_path = "/usr/lib/php:/usr/lib/pear"

then add the `lib/` directory to it:

    include_path = "/usr/lib/php:/usr/lib/pear:/path/to/php-opencloud/lib"

Further Reading
---------------
The file
[docs/quickref.md](php-opencloud/blob/master/docs/quickref.md)
contains a Quick Reference
guide to the
**php-opencloud** library.

The source for the "Getting Started with **php-opencloud**" document (the
user's guide) starts in
[docs/userguide/index.md](php-opencloud/blob/master/docs/userguide/index.md).

There is a complete (auto-generated) API reference manual in the
docs/api directory. Start with docs/api/index.html.

See the [HOWTO.md](php-opencloud/blob/master/HOWTO.md) file for instructions on
regenerating the documentation and running tests.

See the [smoketest.php](php-opencloud/blob/master/smoketest.php) file for some
simple, working examples. This is a test we run before builds to ensure that all
the core functionality is still working after code changes.

The [samples/](php-opencloud/tree/master/samples/) directory has a collection
of tested, working sample code. Note that these may create objects in your cloud
for which you could be charged.

