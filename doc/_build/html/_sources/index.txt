Welcome to php-opencloud!
=========================

Installation
------------

You must install this library through Composer:

.. code-block:: bash

  composer require rackspace/php-opencloud


If you do not have Composer installed, please consult the `official docs
<https://getcomposer.org/doc/00-intro.md>`_.

Once you have installed the library, you will need to load Composer's autoloader
(which registers all the required namespaces). To do this, place the following
line of PHP code at the top of your application's PHP files:

.. code-block:: php

  require 'vendor/autoload.php';

This assumes your application's PHP files are located in the same folder as
``vendor/``. If your files are located elsewhere, please supply the path to
``vendor/autoload.php`` in the require statement above.

Read the :doc:`getting-started-with-openstack` or
:doc:`getting-started-with-rackspace` to help you get started with basic
Compute operations.

.. note::

  If you are running PHP 5.3, please see our :doc:`using-php-5.3` guide.

Services
--------

.. toctree::
  :glob:
  :maxdepth: 1

  services/**/index

Usage tips
----------

.. toctree::
  :maxdepth: 1

  debugging
  caching-creds
  iterators
  regions
  url-types
  logging
  http-clients
  auth

Help and support
----------------

If you have specific problems or bugs with this SDK, please file an issue on
our official `Github <https://github.com/rackspace/php-opencloud>`_. We also
have a `mailing list <https://groups.google.com/forum/#!forum/php-opencloud>`_,
so feel free to join to keep up to date with all the latest changes and
announcements to the library.

For general feedback and support requests, send an email to
sdk-support@rackspace.com.

You can also find assistance via IRC on #rackspace at freenode.net.

Contributing
------------

If you'd like to contribute to the project, or require help running the
unit/acceptance tests, please view the `contributing guidelines
<https://github.com/rackspace/php-opencloud/blob/master/CONTRIBUTING.md>`_.
