Metadata
========

.. include:: lb-setup.sample.rst

List metadata
-------------

.. code-block:: php

  $metadataList = $loadBalancer->metadataList();

  foreach ($metadataList as $metadataItem) {
      printf("Key: %s, Value: %s", $metadataItem->key, $metadataItem->value);
  }


Add metadata
------------

.. code-block:: php

  $metadataItem = $loadBalancer->metadata();
  $metadataItem->create(array(
      'key'   => 'foo',
      'value' => 'bar'
  ));


Modify metadata
---------------

.. code-block:: php

  $metadataItem = $loadBalancer->metadata('foo');
  $metadataItem->update(array(
      'value' => 'baz'
  ));


Remove metadata
---------------

.. code-block:: php

  $metadataItem->delete();
