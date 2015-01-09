Images
======

A virtual machine image is a single file which contains a virtual disk
that has an installed bootable operating system. In the Cloud Images
API, an image is represented by a JSON-encoded data structure (the image
schema) and its raw binary data (the image file).

An Image is represented by the ``OpenCloud\Image\Resource\Image`` class.

Setup
-----

You instantiate an Image object from its ``OpenCloud\Image\Service``
class, which is available from the OpenStack/Rackspace client:

.. code:: php

    $service = $client->imageService('cloudImages', 'IAD');

View the guides for more information about `clients <../Clients.md>`__
or `services <../Services.md>`__.

List images
-----------

.. code:: php

    $images = $service->listImages();

    foreach ($images as $image) {
       /** @param $image OpenCloud\Image\Resource\Image */
    }

For more information about working with iterators, please see the
`iterators documentation <../Iterators.md>`__.

Get image details
-----------------

.. code:: php

    /** @param $image OpenCloud\Image\Resource\Image */
    $image = $service->getImage('<image_id>');

A note on schema classes
~~~~~~~~~~~~~~~~~~~~~~~~

Both ``OpenCloud\Image\Resource\Image`` and
``OpenCloud\Image\Resource\Member`` extend the
``AbstractSchemaResource`` abstract class, which offers some unique
functionality.

Because these resources are inherently dynamic - i.e. they are modelled
on dynamic JSON schema - you need to access their state in a way
different than conventional getter/setter methods, and even class
properties. For this reason, they implement SPL's native
```ArrayAccess`` <http://www.php.net/manual/en/class.arrayaccess.php>`__
interface which allows you to access their state as a conventional
array:

.. code:: php

    $image = $service->getImage('<image_id>');

    $id = $image['id'];
    $tags = $image['tags'];

Update image
------------

You can only update your own custom images - you cannot update or delete
base images. The way in which you may update your image is dictated by
its `schema <Schemas.md>`__.

Although you should be able to add new and replace existing properties,
always prepare yourself for a situation where it might be forbidden:

.. code:: php

    use OpenCloud\Common\Exceptions\ForbiddenOperationException;

    try {
        $image->update(array(
            'name'        => 'foo',
            'newProperty' => 'bar'
        ));
    } catch (ForbiddenOperationException $e) {
        // A 403 Forbidden was returned
    }

There are three operations that can take place for each Image property:

-  If a ``false`` or ``null`` value is provided, a ``REMOVE`` operation
   will occur, removing the property from the JSON document
-  If a non-false value is provided and the property does not exist, an
   ``ADD`` operation will add it to the document
-  If a non-false value is provided and the property does exist, a
   ``REPLACE`` operation will modify the property in the document

Delete image
------------

.. code:: php

    $image->delete();

