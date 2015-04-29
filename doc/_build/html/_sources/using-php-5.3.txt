Using the SDK with PHP v5.3
===========================

Since PHP 5.3 has entered EOL and no longer receives security updates, we have bumped the minimum requirement to 5.4. Using 5.3 is still possible, however, but you will need to use an older stable version of the SDK. There are two ways to do this.

The first way is by requiring it through the command line:

.. code-block:: bash

  composer require rackspace/php-opencloud:1.12

The second way is by updating your composer.json file, and specifying the appropriate version of the SDK:

.. code-block:: json

  "require": {
    "rackspace/php-opencloud": "~1.12"
  }

Note that **1.12** is the last minor release supporting PHP 5.3. Version 1.13 and above has shifted to PHP 5.4.
