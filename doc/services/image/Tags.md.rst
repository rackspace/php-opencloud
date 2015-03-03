Image tags
==========

An image tag is a string of characters used to identify a specific image
or images.

Setup
-----

All member operations are executed against an `Image <Images.md>`__, so
you will need to set this up first.

Add image tag
-------------

.. code:: php

    /** @param $response Guzzle\Http\Message\Response */
    $response = $image->addTag('jamie_dev');

Delete image tag
----------------

.. code:: php

    /** @param $response Guzzle\Http\Message\Response */
    $response = $image->deleteTag('jamie_dev');

