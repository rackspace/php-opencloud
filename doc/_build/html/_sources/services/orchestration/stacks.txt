Stacks
======

A stack is a running instance of a template. When a stack is created,
the `resources <#stack-resources>`__ specified in the template are
created.


Preview stack
-------------

Before you create a stack from a template, you might want to see what
that stack will look like. This is called *previewing the stack*.

This operation takes one parameter, an associative array, with the
following keys:

+-------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| Name              | Description                                                                                                                                                                                                         | Data type                                                                                                               | Required?                             | Default value   | Example value                                                                                   |
+===================+=====================================================================================================================================================================================================================+=========================================================================================================================+=======================================+=================+=================================================================================================+
| ``name``          | Name of the stack                                                                                                                                                                                                   | String. Must start with an alphabetic character, and must contain only alphanumeric, ``_``, ``-`` or ``.`` characters   | Yes                                   | -               | ``simple-lamp-setup``                                                                           |
+-------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``template``      | Template contents                                                                                                                                                                                                   | String. JSON or YAML                                                                                                    | No, if ``templateUrl`` is specified   | ``null``        | ``heat_template_version: 2013-05-23\ndescription: LAMP server\n``                               |
+-------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``templateUrl``   | URL of the template file                                                                                                                                                                                            | String. HTTP or HTTPS URL                                                                                               | No, if ``template`` is specified      | ``null``        | ``https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml``   |
+-------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``parameters``    | Arguments to the template, based on the template's parameters. For example, see the parameters in `this template section <https://github.com/rackspace-orchestration-templates/lamp/blob/master/lamp.yaml#L22>`__   | Associative array                                                                                                       | No                                    | ``null``        | ``array('flavor_id' => 'general1-1')``                                                          |
+-------------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+-------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+

Preview a stack from a template file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored on your local computer as a JSON or YAML
file, you can use it to preview a stack as shown in the following
example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack = $orchestrationService->previewStack(array(
      'name'         => 'simple-lamp-setup',
      'template'     => file_get_contents(__DIR__ . '/lamp.yml'),
      'parameters'   => array(
          'server_hostname' => 'web01',
          'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
      )
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/preview-stack-from-template-file.php>`_


Preview a stack from a template URL
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored as a JSON or YAML file in a remote location
accessible via HTTP or HTTPS, you can use it to preview a stack as shown
in the following example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack = $orchestrationService->previewStack(array(
      'name'         => 'simple-lamp-setup',
      'templateUrl'  => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
      'parameters'   => array(
          'server_hostname' => 'web01',
          'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
      )
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/preview-stack-from-template-url.php>`_


Create stack
------------

You can create a stack from a template. This operation takes one parameter, an
associative array, with the following keys:

+-------------------+--------------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| Name              | Description                                                        | Data type                                                                                                                | Required?                             | Default value   | Example value                                                                                   |
+===================+====================================================================+==========================================================================================================================+=======================================+=================+=================================================================================================+
| ``name``          | Name of the stack                                                  | String. Must start with an alphabetic character, and must contain only alphanumeric, ``_``, ``-`` or ``.`` characters.   | Yes                                   | -               | ``simple-lamp-setup``                                                                           |
+-------------------+--------------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``template``      | Template contents                                                  | String. JSON or YAML                                                                                                     | No, if ``templateUrl`` is specified   | ``null``        | ``heat_template_version: 2013-05-23\ndescription: LAMP server\n``                               |
+-------------------+--------------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``templateUrl``   | URL of template file                                               | String. HTTP or HTTPS URL                                                                                                | No, if ``template`` is specified      | ``null``        | ``https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml``   |
+-------------------+--------------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``parameters``    | Arguments to the template, based on the template's parameters      | Associative array                                                                                                        | No                                    | ``null``        | ``array('server_hostname' => 'web01')``                                                         |
+-------------------+--------------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+
| ``timeoutMins``   | Duration, in minutes, after which stack creation should time out   | Integer                                                                                                                  | Yes                                   | -               | 5                                                                                               |
+-------------------+--------------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+---------------------------------------+-----------------+-------------------------------------------------------------------------------------------------+

Create a stack from a template file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored on your local computer as a JSON or YAML
file, you can use it to create a stack as shown in the following
example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack = $orchestrationService->createStack(array(
      'name'         => 'simple-lamp-setup',
      'templateUrl'  => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
      'parameters'   => array(
          'server_hostname' => 'web01',
          'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
      ),
      'timeoutMins'  => 5
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/create-stack-from-template-file.php>`_


Create a stack from a template URL
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored as a JSON or YAML file in a remote location
accessible via HTTP or HTTPS, you can use it to create a stack as shown
in the following example:

.. code-block:: php

  $stack = $orchestrationService->stack();
  $stack->create(array(
      'name'          => 'simple-lamp-setup',
      'templateUrl'   => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
      'parameters'    => array(
          'server_hostname' => 'web01',
          'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
      ),
      'timeoutMins'   => 5
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/create-stack-from-template-url.php>`_

List stacks
-----------

You can list all the stacks that you have created as shown in the
following example:

.. code-block:: php

  $stacks = $orchestrationService->listStacks();
  foreach ($stacks as $stack) {
      /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/list-stacks.php>`_


Get stack
---------

You can retrieve a specific stack using its name, as shown in the
following example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack = $orchestrationService->getStack('simple-lamp-setup');

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-stack.php>`_


Get stack template
------------------

You can retrieve the template used to create a stack. Note that a JSON
string is returned, regardless of whether a JSON or YAML template was
used to create the stack.

.. code-block:: php

  /** @var $stackTemplate string **/
  $stackTemplate = $stack->getTemplate();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/get-stack-template.php>`_


Update stack
------------

You can update a running stack.

This operation takes one parameter, an associative array, with the
following keys:

+-------------------+------------------------------------------------------------------+-----------------------------+---------------------------------------+-----------------+---------------------------------------------------------------------------------------------------------+
| Name              | Description                                                      | Data type                   | Required?                             | Default value   | Example value                                                                                           |
+===================+==================================================================+=============================+=======================================+=================+=========================================================================================================+
| ``template``      | Template contents                                                | String. JSON or YAML        | No, if ``templateUrl`` is specified   | ``null``        | ``heat_template_version: 2013-05-23\ndescription: LAMP server\n``                                       |
+-------------------+------------------------------------------------------------------+-----------------------------+---------------------------------------+-----------------+---------------------------------------------------------------------------------------------------------+
| ``templateUrl``   | URL of template file                                             | String. HTTP or HTTPS URL   | No, if ``template`` is specified      | ``null``        | ``https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp-updated.yaml``   |
+-------------------+------------------------------------------------------------------+-----------------------------+---------------------------------------+-----------------+---------------------------------------------------------------------------------------------------------+
| ``parameters``    | Arguments to the template, based on the template's parameters    | Associative array           | No                                    | ``null``        | ``array('flavor_id' => 'general1-1')``                                                                  |
+-------------------+------------------------------------------------------------------+-----------------------------+---------------------------------------+-----------------+---------------------------------------------------------------------------------------------------------+
| ``timeoutMins``   | Duration, in minutes, after which stack update should time out   | Integer                     | Yes                                   | -               | 5                                                                                                       |
+-------------------+------------------------------------------------------------------+-----------------------------+---------------------------------------+-----------------+---------------------------------------------------------------------------------------------------------+


Update a stack from a template file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored on your local computer as a JSON or YAML
file, you can use it to update a stack as shown in the following
example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack->update(array(
      'template'      => file_get_contents(__DIR__ . '/lamp-updated.yml'),
      'parameters'    => array(
          'server_hostname' => 'web01',
          'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
      ),
      'timeoutMins'   => 5
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/update-stack-from-template-file.php>`_


Update Stack from Template URL
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored as a JSON or YAML file in a remote location
accessible via HTTP or HTTPS, you can use it to update a stack as shown
in the following example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack->update(array(
      'templateUrl'   => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp-updated.yaml',
      'parameters'    => array(
          'server_hostname' => 'web01',
          'image'           => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
      ),
      'timeoutMins'   => 5
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/update-stack-from-template-url.php>`_


Delete stack
------------

If you no longer need a stack and all its resources, you can delete the
stack *and* the resources as shown in the following example:

.. code-block:: php

  $stack->delete();

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/delete-stack.php>`_


Abandon Stack
-------------

.. note::

  This operation returns data about the abandoned stack as a string. You can
  use this data to recreate the stack by using the `adopt stack <#adopt-stack>`_
  operation.

If you want to delete a stack but preserve all its resources, you can
abandon the stack as shown in the following example:

.. code-block:: php

  /** @var $abandonStackData string **/
  $abandonStackData = $stack->abandon();
  file_put_contents(__DIR__ . '/sample_adopt_stack_data.json', $abandonStackData);

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/abandon-stack.php>`_


Adopt stack
-----------

If you have data from an abandoned stack, you can re-create the stack as
shown in the following example:

.. code-block:: php

  /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
  $stack = $orchestrationService->adoptStack(array(
      'name'           => 'simple-lamp-setup',
      'template'       => file_get_contents(__DIR__ . '/lamp.yml'),
      'adoptStackData' => $abandonStackData,
      'timeoutMins'    => 5
  ));

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/adopt-stack.php>`_
