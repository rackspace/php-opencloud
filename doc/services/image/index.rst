Images v1
=========

Setup
-----

.. include:: ../common/rs-client.sample.rst

.. code-block:: php

  $service = $client->imageService(null, '{region}');


Operations
----------

.. toctree::

  images
  schemas
  sharing
  tags


Glossary
--------

  image
    A virtual machine image is a single file which contains a virtual disk
    that has an installed bootable operating system. In the Cloud Images
    API, an image is represented by a JSON-encoded data structure (the image
    schema) and its raw binary data (the image file).

  schema
    The Cloud Images API supplies JSON documents describing the JSON-encoded
    data structures that represent domain objects, so that a client knows
    exactly what to expect in an API response.

  tag
    An image tag is a string of characters used to identify a specific image
    or images.
