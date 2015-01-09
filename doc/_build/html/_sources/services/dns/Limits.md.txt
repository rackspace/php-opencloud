Limits
======

Setup
-----

Limit methods will be called on the DNS service, an instance of
``OpenCloud\DNS\Service``. Please see the `DNS service <Service.md>`__
documentation for setup instructions.

List all limits
---------------

This call provides a list of all applicable limits for the specified
account.

.. code:: php

    $limits = $service->limits();

Absolute limits
~~~~~~~~~~~~~~~

There are some absolute limits imposed on your account - such as how
many domains you can create and how many records you can create for each
domain:

.. code:: php

    $absoluteLimits = $limits->absolute;

    # Domain limit
    echo $absoluteLimits->domains;

    # Record limit per domain
    echo $absoluteLimits->{'records per domain'};

List limit types
----------------

To find out the different limit types you can query, run:

.. code:: php

    $limitTypes = $service->limitTypes();

will return:

::

    array(3) {
      [0] =>
      string(10) "RATE_LIMIT"
      [1] =>
      string(12) "DOMAIN_LIMIT"
      [2] =>
      string(19) "DOMAIN_RECORD_LIMIT"
    }

Query a specific limit
----------------------

.. code:: php

    $limit = $service->limits('DOMAIN_LIMIT');

    echo $limit->absolute->limits->value;

    >>> 500

