Agents
======

Intro
-----

The Monitoring Agent resides on the host server being monitored. The
agent allows you to gather on-host metrics based on agent checks and
push them to Cloud Monitoring where you can analyze them, use them with
the Cloud Monitoring infrastructure (such as alarms), and archive them.

For more information about this feature, including a brief overview of
its core design principles and security layers, see the `official API
documentation <http://docs.rackspace.com/cm/api/v1.0/cm-devguide/content/service-agent.html>`__.

Setup
-----

.. code:: php

    $agentId = '00-agent.example.com';
    $agent   = $service->getAgent($agentId);

You can view the `service page <Service.md>`__ for more information
about setting up the Cloud Monitoring service.

List agents
-----------

.. code:: php

    $agents = $service->getAgents();

    foreach ($agents as $agent) {
       echo $agent->getLastConnected();
    }

Please consult the `iterator doc <docs/userguide/Iterators.md>`__ for
more information about iterators.

List connections
----------------

.. code:: php

    $connections = $agent->getConnections();

Please consult the `iterator doc <docs/userguide/Iterators.md>`__ for
more information about iterators.

Get connection
--------------

.. code:: php

    /** @var \OpenCloud\CloudMonitoring\Resource\AgentConnection */
    $connection = $agent->getConnection('cntl4qsIbA');

Agent Connection properties
~~~~~~~~~~~~~~~~~~~~~~~~~~~

+--------------------+---------------+--------+---------------------------+
| Name               | Description   | Type   | Method                    |
+====================+===============+========+===========================+
| id                 | -             | -      | ``getId()``               |
+--------------------+---------------+--------+---------------------------+
| guid               | -             | -      | ``getGuid()``             |
+--------------------+---------------+--------+---------------------------+
| agent\_id          | -             | -      | ``getAgentId()``          |
+--------------------+---------------+--------+---------------------------+
| endpoint           | -             | -      | ``getEndpoint()``         |
+--------------------+---------------+--------+---------------------------+
| process\_version   | -             | -      | ``getProcessVersion()``   |
+--------------------+---------------+--------+---------------------------+
| bundle\_version    | -             | -      | ``getBundleVersion()``    |
+--------------------+---------------+--------+---------------------------+
| agent\_ip          | -             | -      | ``getAgentIp()``          |
+--------------------+---------------+--------+---------------------------+

Agent tokens
============

Intro
-----

Agent tokens are used to authenticate Monitoring agents to the
Monitoring Service. Multiple agents can share a single token.

Setup
-----

.. code:: php

    $tokenId = '4c5e28f0-0b3f-11e1-860d-c55c4705a286:1234';
    $agentToken = $service->getAgentToken($tokenId);

Create agent token
------------------

.. code:: php

    $newToken = $service->getAgentToken();
    $newToken->create(array('label' => 'Foobar'));

List agent tokens
-----------------

.. code:: php

    $agentTokens = $service->getAgentTokens();

    foreach ($agentTokens as $token) {
        echo $token->getLabel();
    }

Please consult the `iterator doc <docs/userguide/Iterators.md>`__ for
more information about iterators.

Update and delete Agent Token
-----------------------------

.. code:: php

    // Update
    $token->update(array(
        'label' => 'New label'
    ));

    // Delete
    $token->delete();

Agent Host Information
======================

Info
----

An agent can gather host information, such as process lists, network
configuration, and memory usage, on demand. You can use the
host-information API requests to gather this information for use in
dashboards or other utilities.

Setup
-----

.. code:: php

    $host = $monitoringService->getAgentHost();

Get some metrics
----------------

.. code:: php

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

Info
----

Each agent check type gathers data for a related set of target devices
on the server where the agent is installed. For example,
``agent.network`` gathers data for network devices. The actual list of
target devices is specific to the configuration of the host server. By
focusing on specific targets, you can efficiently narrow the metric data
that the agent gathers.

List agent targets
~~~~~~~~~~~~~~~~~~

.. code:: php

    $targets = $service->getAgentTargets();

    foreach ($targets as $target) {
        echo $target->getType();
    }

