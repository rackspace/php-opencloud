Object Store
============

**Object Store** is an object-based storage system that stores content
and metadata as objects in a cloud.

Specifically, a cloud is made up of one or more regions. Each region can
have several **containers**, created by a user. Each container can
container several **objects** (sometimes referred to as files), uploaded
by the user.

Getting started
---------------

1. Instantiate an OpenStack or Rackspace client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Choose one of the following two options:

-  If you are working with a vanilla OpenStack cloud, instantiate an
   ``OpenCloud\OpenStack`` client as shown below.

   .. code:: php

       use OpenCloud\OpenStack;

        $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
            'username' => '<YOUR OPENSTACK USERNAME>',
            'password' => '<YOUR OPENSTACK PASSWORD>'
        ));

-  If you are working with the Rackspace cloud, instantiate a
   ``OpenCloud\Rackspace`` client as shown below.

   .. code:: php

       use OpenCloud\Rackspace;

       $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
           'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
           'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
        ));

2. Obtain an Object Store service object from the client.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $region = 'DFW';
    $objectStoreService = $client->objectStoreService(null, $region);

In the example above, you are connecting to the ``DFW`` region of the
cloud. Any containers and objects created with this
``$objectStoreService`` instance will be stored in that cloud region.

3. Create a container for your objects (also referred to as files).
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $container = $objectStoreService->createContainer('logos');

    **Note:** when working with names that contain non-standard
    alphanumerical characters (such as spaces or non-English
    characters), you must ensure they are encoded with
    ```urlencode`` <http://php.net/urlencode>`__ before passing them in

4. Upload an object to the container.
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code:: php

    $localFileName  = '/path/to/local/php-elephant.jpg';
    $remoteFileName = 'php-elephant.jpg';

    $fileData = fopen($localFileName, 'r');
    $container->uploadObject($remoteFileName, $fileData);

[ `Get the executable PHP script for this
example </samples/ObjectStore/quickstart.php>`__ ]

Next steps
----------

There is a lot more you can do with containers and objects. See the
`complete user guide to the Object Store service <USERGUIDE.md>`__.
