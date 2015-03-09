Images
======

List images
-----------

.. code-block:: php

  $images = $service->listImages();

  foreach ($images as $image) {
     /** @param $image OpenCloud\Image\Resource\Image */
  }


Get image details
-----------------

.. code-block:: php

  /** @param $image OpenCloud\Image\Resource\Image */
  $image = $service->getImage('{imageId}');


A note on schema classes
~~~~~~~~~~~~~~~~~~~~~~~~

Both ``OpenCloud\Image\Resource\Image`` and ``OpenCloud\Image\Resource\Member``
extend the ``AbstractSchemaResource`` class, which offers some unique functionality.

Because these resources are inherently dynamic - i.e. they are modelled
on dynamic JSON schema - you need to access their state in a different way
than conventional getter/setter methods, and even class properties. For this
reason, they implement SPL's native
`ArrayAccess <http://www.php.net/manual/en/class.arrayaccess.php>`_
interface which allows you to access their state as a conventional
array:

.. code-block:: php

  $image = $service->getImage('{imageId}');

  $id = $image['id'];
  $tags = $image['tags'];


Update image
------------

You can only update your own custom images - you cannot update or delete
base images. The way in which you may update your image is dictated by
its `schema <schemas>`__.

Although you should be able to add new and replace existing properties,
always prepare yourself for a situation where it might be forbidden:

.. code-block:: php

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

*  If a ``false`` or ``null`` value is provided, a ``REMOVE`` operation
   will occur, removing the property from the JSON document
*  If a non-false value is provided and the property does not exist, an
   ``ADD`` operation will add it to the document
*  If a non-false value is provided and the property does exist, a
   ``REPLACE`` operation will modify the property in the document

Delete image
------------

.. code-block:: php

  $image->delete();
