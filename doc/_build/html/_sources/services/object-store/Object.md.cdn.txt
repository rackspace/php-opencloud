Setup
-----

You will need to instantiate the container object and access its CDN
functionality as `documented
here <https://github.com/rackspace/php-opencloud/blob/master/docs/userguide/ObjectStore/CDN/Container.md>`__.

Purge CDN-enabled objects
-------------------------

To remove a CDN object from public access:

.. code:: php

    $object->purge();

You can also provide an optional e-mail address (or comma-delimeted list
of e-mails), which the API will send a confirmation message to once the
object has been completely purged:

.. code:: php

    $object->purge('jamie.hannaford@rackspace.com');
    $object->purge('hello@example.com,hallo@example.com');

