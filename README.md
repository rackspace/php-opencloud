php-opencloud
=============
PHP SDK for OpenStack/Rackspace APIs

See the COPYING file for license and copyright information.

The PHP SDK should work with most OpenStack-based cloud deployments,
though it specifically targets the Rackspace public cloud. In
general, whenever a Rackspace deployment is substantially different
than a pure OpenStack one, a separate Rackspace subclass is provided
so that you can still use the SDK with a pure OpenStack instance
(for example, see the `OpenStack` class (for OpenStack) and the
`Rackspace` subclass).

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

Move the files in the `lib/` directory to a location in your PHP's
`include_path` or, conversely, set the `include_path` to point to the
location of the `lib/` files. From within a PHP program, for example,
you can use:

    ini_set('include_path', './lib:'.ini_get('include_path'));

This prepends the local `./lib` directory to the existing `include_path`
value.

### NOTE

This version supports the following components:

* OpenStack Nova (Rackspace Cloud Servers)
* OpenStack Swift (Rackspace Cloud Files, which includes CDN extensions)
* Rackspace Cloud Databases

Further Reading
---------------
The file `docs/quickref.md` contains a Quick Reference guide to the
<b>php-opencloud</b> library.

The source for the "Getting Started with php-opencloud" document (the
user's guide) starts in docs/userguide/index.md.

There is a complete (auto-generated) API reference manual in the
docs/api directory. Start with docs/api/index.html.

See the `HOWTO.md` file for instructions on regenerating the documentation
and running tests.

See the `smoketest.php` file for some simple, working examples. This is a test
we run before builds to ensure that all the core functionality is still
working after code changes.

The `samples/` directory has a collection of tested, working sample code.
Note that these may create objects in your cloud that you could be
charged for.
