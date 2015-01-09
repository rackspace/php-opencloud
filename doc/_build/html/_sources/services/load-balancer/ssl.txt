SSL Termination
===============

The SSL Termination feature allows a load balancer user to terminate SSL
traffic at the load balancer layer versus at the web server layer. A
user may choose to configure SSL Termination using a key and an SSL
certificate or an (Intermediate) SSL certificate.

When SSL Termination is configured on a load balancer, a secure shadow
server is created that listens only for secure traffic on a
user-specified port. This shadow server is only visible to and
manageable by the system. Existing or updated attributes on a load
balancer with SSL Termination will also apply to its shadow server. For
example, if Connection Logging is enabled on an SSL load balancer, it
will also be enabled on the shadow server and Cloud Files logs will
contain log files for both.

.. include:: lb-setup.sample.rst


View configuration
------------------

.. code-block:: php

  /** @var $sslConfig OpenCloud\LoadBalancer\Resource\SSLTermination **/
  $sslConfig = $loadBalancer->SSLTermination();


Update configuration
--------------------

.. code-block:: php

  $sslConfig->update(array(
      'enabled'     => true,
      'securePort'  => 443,
      'privateKey'  => $key,
      'certificate' => $cert
  ));

For a full list, with explanations, of required and optional attributes,
please consult the `official
documentation <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/SSLTermination-d1e2479.html>`__

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/LoadBalancer/ssl-termination.php>`_


Delete configuration
--------------------

.. code-block:: php

  $sslConfig->delete();
