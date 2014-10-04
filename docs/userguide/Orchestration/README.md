# Orchestration

**Orchestration** is a service that can be used to create and manage cloud 
resources. Examples of such resources are databases, load balancers,
servers and software installed on them.

## Concepts

To use the Orchestration service effectively, you should understand several
key concepts:

* **Template**: An Orchestration template is a JSON or YAML document that
describes how a set of resources should be assembled to produce a working
deployment. The template specifies what resources should be used, what
attributes of these resources are parameterized and what information is
output to the user when a template is instantiated.

* **Resource**: A resource is a template artifact that represents some component of your desired architecture (a Cloud Server, a group of scaled Cloud Servers, a load balancer, some configuration management system, and so forth).

* **Stack**: A stack is a running instance of a template. When a stack is created,
the resources specified in the template are created.

## Getting started

### 1. Instantiate an OpenStack or Rackspace client.

Choose one of the following two options:

* If you are working with a vanilla OpenStack cloud, instantiate an `OpenCloud\OpenStack` client as shown below.

    ```php
    use OpenCloud\OpenStack;

     $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
         'username' => '<YOUR OPENSTACK USERNAME>',
         'password' => '<YOUR OPENSTACK PASSWORD>'
     ));
    ```

* If you are working with the Rackspace cloud, instantiate a `OpenCloud\Rackspace` client as shown below.

    ```php
    use OpenCloud\Rackspace;

    $client = new Rackspace(Rackspace::US_IDENTITY_ENDPOINT, array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
     ));
    ```

### 2. Obtain an Orchestration service object from the client.
```php
$region = '<CLOUD REGION NAME>';
$orchestrationService = $client->orchestrationService(null, $region);
```

In the example above, you are connecting to the ``DFW`` region of the cloud. Any stacks and resources created with this `$orchestrationService` instance will be stored in that cloud region.

### 3. Create a stack from a template.
```php
$stack = $orchestrationService->createStack(array(
    'stack_name'   => 'Cloud server with attached block storage',
    'template_url' => 'https://raw.githubusercontent.com/openstack/heat-templates/master/hot/vm_with_cinder.yaml',
    'parameters'   => array(
        'key_name' => 'mine',
        'flavor'   => 'performance1_1',
        'image'    => '0112b238-4267-4a22-9785-fcf75814bc2f' // Ubuntu 14.04 LTS (Trusty Tahr)
    ),
    'timeout_mins' => 5
));
```

[ [Get the executable PHP script for this example](/samples/Orchestration/quickstart.php) ]

## Next steps

Once you have created a stack, there is more you can do with it. See [complete user guide for orchestration](USERGUIDE.md).
