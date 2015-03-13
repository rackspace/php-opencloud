Templates
=========

An Orchestration template is a JSON or YAML document that describes how
a set of resources should be assembled to produce a working deployment
(known as a `stack <#stacks>`__). The template specifies the resources
to use, the attributes of these resources that are parameterized and the
information that is sent to the user when a template is instantiated.

Validating templates
--------------------

Before you use a template to create a stack, you might want to validate it.


Validate a template from a file
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored on your local computer as a JSON or YAML
file, you can validate it as shown in the following example:

.. code-block:: php

  use OpenCloud\Common\Exceptions\InvalidTemplateError;

  try {
      $orchestrationService->validateTemplate(array(
          'template' => file_get_contents(__DIR__ . '/lamp.yaml')
      ));
  } catch (InvalidTemplateError $e) {
      // Use $e->getMessage() for explanation of why template is invalid
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/validate-template-from-template-url.php>`_

Validate Template from URL
~~~~~~~~~~~~~~~~~~~~~~~~~~

If your template is stored as a JSON or YAML file in a remote location
accessible via HTTP or HTTPS, you can validate it as shown in the
following example:

.. code-block:: php

  use OpenCloud\Common\Exceptions\InvalidTemplateError;

  try {
      $orchestrationService->validateTemplate(array(
          'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml'
      ));
  } catch (InvalidTemplateError $e) {
      // Use $e->getMessage() for explanation of why template is invalid
  }

`Get the executable PHP script for this example <https://raw.githubusercontent.com/rackspace/php-opencloud/master/samples/Orchestration/validate-template-from-template-url.php>`_
