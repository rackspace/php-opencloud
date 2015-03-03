Orchestration
=============

**Orchestration** is a service that can be used to create and manage
cloud resources. Examples of such resources are databases, load
balancers, servers and software installed on them.

Concepts
--------

To use the Orchestration service effectively, you should understand
several key concepts:

-  **Template**: An Orchestration template is a JSON or YAML document
   that describes how a set of resources should be assembled to produce
   a working deployment. The template specifies what resources should be
   used, what attributes of these resources are parameterized and what
   information is output to the user when a template is instantiated.

-  **Resource**: A resource is a template artifact that represents some
   component of your desired architecture (a Cloud Server, a group of
   scaled Cloud Servers, a load balancer, some configuration management
   system, and so forth).

-  **Stack**: A stack is a running instance of a template. When a stack
   is created, the resources specified in the template are created.

Getting started
---------------

1. Instantiate an OpenStack or Rackspace client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

To use the Orchestration service, you must first instantiate a
``OpenStack`` or ``Rackspace`` client object.

-  If you are working with an OpenStack cloud, instantiate an
   ``OpenCloud\OpenStack`` client as follows:

   .. code:: php

       use OpenCloud\OpenStack;

       $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
           'username' => '<YOUR OPENSTACK CLOUD ACCOUNT USERNAME>',
           'password' => '<YOUR OPENSTACK CLOUD ACCOUNT PASSWORD>'
       ));

-  If you are working with the Rackspace cloud, instantiate a
   ``OpenCloud\Rackspace`` client as follows:

   .. code:: php

       use OpenCloud\Rackspace;

       $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
           'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
           'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
        ));

2. Obtain an Orchestration service object from the client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

All Orchestration operations are done via an *orchestration service
object*. To instantiate this object, call the ``orchestrationService``
method on the ``$client`` object as shown in the following example:

.. code:: php

    $region = '<CLOUD REGION NAME>';
    $orchestrationService = $client->orchestrationService(null, $region);

Any stacks and resources created with this ``$orchestrationService``
instance will be stored in the cloud region specified by ``$region``.

3. Create a stack from a template.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $stack = $orchestrationService->createStack(array(
        'name'         => 'simple-lamp-setup',
        'templateUrl'  => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
        'parameters'   => array(
            'server_hostname' => 'web01',
            'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
        ),
        'timeoutMins'  => 5
    ));

[ `Get the executable PHP script for this
example </samples/Orchestration/quickstart.php>`__ ]

Next steps
----------

Once you have created a stack, there is more you can do with it. See
`complete user guide for orchestration <USERGUIDE.md>`__.
