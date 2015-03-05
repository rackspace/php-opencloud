Agents
======

The Monitoring Agent resides on the host server being monitored. The
agent allows you to gather on-host metrics based on agent checks and
push them to Cloud Monitoring where you can analyze them, use them with
the Cloud Monitoring infrastructure (such as alarms), and archive them.

For more information about this feature, including a brief overview of
its core design principles and security layers, see the `official API
documentation <http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/service-agent.html>`__.

Retrieve details about an agent
-------------------------------

.. code-block:: php

  $agent = $service->getAgent('{agentId}');


List agents
-----------

.. code-block:: php

  $agents = $service->getAgents();

  foreach ($agents as $agent) {
     echo $agent->getLastConnected();
  }


List connections
----------------

.. code-block:: php

  $connections = $agent->getConnections();


Get connection
--------------

.. code-block:: php

    /** @var \OpenCloud\CloudMonitoring\Resource\AgentConnection */
    $connection = $agent->getConnection('{connectionId}');


Once you have access to an agent's ``OpenCloud\CloudMonitoring\Resource\AgentConnection``
object, these are the attributes you can access:

+--------------------+---------------------------+
| Name               | Method                    |
+====================+===========================+
| id                 | ``getId()``               |
+--------------------+---------------------------+
| guid               | ``getGuid()``             |
+--------------------+---------------------------+
| agent_id           | ``getAgentId()``          |
+--------------------+---------------------------+
| endpoint           | ``getEndpoint()``         |
+--------------------+---------------------------+
| process_version    | ``getProcessVersion()``   |
+--------------------+---------------------------+
| bundle_version     | ``getBundleVersion()``    |
+--------------------+---------------------------+
| agent_ip           | ``getAgentIp()``          |
+--------------------+---------------------------+

Agent tokens
============

Agent tokens are used to authenticate Monitoring agents to the
Monitoring Service. Multiple agents can share a single token.

Retrieve an agent token
-----------------------

.. code-block:: php

  $agentToken = $service->getAgentToken('{tokenId}');


Create agent token
------------------

.. code-block:: php

  $newToken = $service->getAgentToken();
  $newToken->create(array('label' => 'Foobar'));


List agent tokens
-----------------

.. code-block:: php

  $agentTokens = $service->getAgentTokens();

  foreach ($agentTokens as $token) {
      echo $token->getLabel();
  }


Update agent token
------------------

.. code-block:: php

  $token->update(array(
      'label' => 'New label'
  ));


Update agent token
------------------

.. code-block:: php

  $token->delete();


Agent Host Information
======================

An agent can gather host information, such as process lists, network
configuration, and memory usage, on demand. You can use the
host-information API requests to gather this information for use in
dashboards or other utilities.

Setup
-----

.. code-block:: php

  $host = $service->getAgentHost();


Get some metrics
----------------

.. code-block:: php

  $cpuInfo        = $host->info('cpus');
  $diskInfo       = $host->info('disks');
  $filesystemInfo = $host->info('filesystems');
  $memoryInfo     = $host->info('memory');
  $networkIntInfo = $host->info('network_interfaces');
  $processesInfo  = $host->info('processes');
  $systemInfo     = $host->info('system');
  $userInfo       = $host->info('who');

  // What CPU models do we have?
  foreach ($cpuInfo as $cpuMetric) {
      echo $cpuMetric->model, PHP_EOL;
  }

  // How many disks do we have?
  echo $diskInfo->count();

  // What's the available space on our ext4 filesystem?
  foreach ($filesystemInfo as $filesystemMetric) {
      if ($filesystemMetric->sys_type_name == 'ext4') {
          echo $filesystemMetric->avail;
      }
  }

Agent targets
=============

Each agent check type gathers data for a related set of target devices
on the server where the agent is installed. For example,
``agent.network`` gathers data for network devices. The actual list of
target devices is specific to the configuration of the host server. By
focusing on specific targets, you can efficiently narrow the metric data
that the agent gathers.

List agent targets
------------------

.. code-block:: php

  $targets = $service->getAgentTargets();

  foreach ($targets as $target) {
      echo $target->getType();
  }
