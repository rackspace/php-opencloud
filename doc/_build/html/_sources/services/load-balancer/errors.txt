Error Pages
===========

.. include:: lb-setup.sample.rst

An error page is the html file that is shown to the end user when an error in
the service has been thrown. By default every virtual server is provided with
the default error file. It is also possible to set a custom error page for a
load balancer.


View Error Page Content
-----------------------

.. code-block:: php

    $errorPage = $loadBalancer->errorPage();
    $errorPageContent = $errorPage->content;

    /** @var $errorPageContent string **/

In the example above the value of ``$errorPageContent`` is the HTML for
that page. This could either be the HTML of the default error page or of
your custom error page.


Set Custom Error Page
---------------------

.. code-block:: php

  $errorPage = $loadBalancer->errorPage();
  $errorPage->update(array(
      'content' => '<HTML content of custom error page>'
  ));


Delete Custom Error Page
------------------------

.. code-block:: php

  $errorPage = $loadBalancer->errorPage();
  $errorPage->delete();
