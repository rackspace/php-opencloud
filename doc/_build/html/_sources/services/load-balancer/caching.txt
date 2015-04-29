Content Caching
===============

When content caching is enabled on a load balancer, recently-accessed files are
stored on the load balancer for easy retrieval by web clients. Requests to the
load balancer for these files are serviced by the load balancer itself, which
reduces load off its back-end nodes and improves response times as well.


.. include:: lb-setup.sample.rst


Check Configuration
-------------------

.. code-block:: php

  // TRUE if enabled, FALSE if not
  $contentCaching = $loadBalancer->hasContentCaching();


Enable Content Caching
----------------------

.. code-block:: php

  $loadBalancer->enableContentCaching(true);


Disable Content Caching
-----------------------

.. code-block:: php

  $loadBalancer->enableContentCaching(false);
