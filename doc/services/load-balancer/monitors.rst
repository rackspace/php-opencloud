Health Monitors
===============

.. include:: lb-setup.sample.rst

Retrieve monitor details
------------------------

.. code-block:: php

  /** @var $healthMonitor OpenCloud\LoadBalancer\Resource\HealthMonitor **/

  $healthMonitor = $loadBalancer->healthMonitor();

  printf(
      "Monitoring type: %s, delay: %s, timeout: %s, attempts before deactivation: %s",
      $healthMonitor->type, $healthMonitor->delay, $healthMonitor->timeout
  );

For a full list, with explanations, of required and optional attributes,
please consult the `official
documentation <http://docs.rackspace.com/loadbalancers/api/v1.0/clb-devguide/content/Monitor_Connections-d1e3536.html>`__


Update monitor
--------------

.. code-block:: php

  $healthMonitor->update(array(
      'delay'   => 120,
      'timeout' => 60,
      'type'    => 'CONNECT'
      'attemptsBeforeDeactivation' => 3
  ));


Delete monitor
--------------

.. code-block:: php

  $healthMonitor->delete();
