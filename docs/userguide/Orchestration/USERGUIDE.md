# The Complete User Guide to the Orchestration Service

Orchestration is a service that can be used to create and manage cloud resources.
Examples of such resources are databases, load balancers, servers and software
installed on them.

## Concepts

To use the Orchestration service effectively, you should understand several key
concepts:

* **Template**: An Orchestration template is a JSON or YAML document that
describes how a set of resources should be assembled to produce a working
deployment. The template specifies what resources should be used, what attributes
of these resources are parameterized and what information is output to the user
when a template is instantiated.

* **Resource**: A resource is a template artifact that represents some component
of your desired architecture (a Cloud Server, a group of scaled Cloud Servers, a
load balancer, some configuration management system, and so forth).

* **Stack**: A stack is a running instance of a template. When a stack is created,
the resources specified in the template are created.

## Prerequisites

### Client
To use the Orchestration service, you must first instantiate a `OpenStack` or `Rackspace` client object.

* If you are working with a vanilla OpenStack cloud, instantiate an `OpenCloud\OpenStack` client as shown below.

    ```php
    use OpenCloud\OpenStack;

    $client = new OpenStack('<OPENSTACK CLOUD IDENTITY ENDPOINT URL>', array(
        'username' => '<YOUR RACKSPACE CLOUD ACCOUNT USERNAME>',
        'apiKey'   => '<YOUR RACKSPACE CLOUD ACCOUNT API KEY>'
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

### Orchestration Service
All orchestration operations are done via an orchestration service object.

```php
$region = 'DFW';
$orchestrationService = $client->orchestrationService(null, $region);
```

In the example above, you are connecting to the ``DFW`` region of the cloud. Any
resources and stacks created with this `$orchestrationService` instance will be
created in that cloud region.

## Templates
An Orchestration template is a JSON or YAML document that
describes how a set of resources should be assembled to produce a working
deployment (known as a [stack](#stacks)). The template specifies what resources
should be used, what attributes of these resources are parameterized and what
information is output to the user when a template is instantiated.

### Validate Template
Prior to actually _using_ a template to create a stack, you may want to validate it.

#### Validate Template from File
If your template is stored on your local computer as a JSON or YAML file, you
can validate it as shown below:

```php
$orchestrationService->validateTemplate(array(
    'template'     => file_get_contents(__DIR__ . '/lamp.yml')
));
```

[ [Get the executable PHP script for this example](/samples/Orchestration/validate-template-from-template-url.php) ]

If the template is invalid, a [`Guzzle\Http\Exception\ClientErrorResponseException`](http://api.guzzlephp.org/class-Guzzle.Http.Exception.ClientErrorResponseException.html) will be thrown.

#### Validate Template from URL
If your template is stored in a remote location accessible via HTTP or HTTPS,
as a JSON or YAML file, you can validate it as shown below:

```php
$orchestrationService->validateTemplate(array(
    'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml'
));
```

[ [Get the executable PHP script for this example](/samples/Orchestration/validate-template-from-template-url.php) ]

If the template is invalid, a [`Guzzle\Http\Exception\ClientErrorResponseException`](http://api.guzzlephp.org/class-Guzzle.Http.Exception.ClientErrorResponseException.html) will be thrown.

## Stacks
A stack is a running instance of a template. When a stack is created, the
[resources](#stack-resources) specified in the template are created.

### Preview Stack
Before _actually_ creating a stack from a template, you may want to simply
get a glimpse of what that stack might look like. This is called previewing the
stack.

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | Name of the stack | String. Must start with a alphabet. Must contain only alphanumeric, `_`, `-` or `.` characters. | Yes | | `simple-lamp-setup` |
| `template` | Template contents | String, JSON or YAML | No, if `templateUrl` is specified | | `heat_template_version: 2013-05-23\ndescription: LAMP server\n` |
| `templateUrl` | URL of template file | String, HTTP or HTTPS URL | No, if `template` is specified | | `https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml` |
| `parameters` | Arguments to the template, based on the template's parameters | Associative array | No | | `array('flavor_id' => 'performance1_1')` |

#### Preview Stack from Template File

If your template is stored on your local computer as a JSON or YAML file, you
can use it to preview a stack as shown below:

```php
$stack = $orchestrationService->previewStack(array(
    'name'        => 'simple-lamp-setup',
    'template'    => file_get_contents(__DIR__ . '/lamp.yml'),
    'parameters'   => array(
        'server_hostname' => 'web01',
        'image' => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
    ),
    'timeoutMins' => 5
));
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```

[ [Get the executable PHP script for this example](/samples/Orchestration/preview-stack-from-template-file.php) ]

#### Preview Stack from Template URL

If your template is stored in a remote location accessible via HTTP or HTTPS,
as a JSON or YAML file, you can use it to preview a stack as shown below:

```php
$stack = $orchestrationService->previewStack(array(
    'name'        => 'simple-lamp-setup',
    'templateUrl' => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
    'parameters'   => array(
        'server_hostname' => 'web01',
        'image' => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
    ),
    'timeoutMins' => 5
));
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```

[ [Get the executable PHP script for this example](/samples/Orchestration/preview-stack-from-template-url.php) ]

### Create Stack
You can create a stack from a template.

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `name` | Name of the stack | String. Must start with a alphabet. Must contain only alphanumeric, `_`, `-` or `.` characters. | Yes | | `simple-lamp-setup` |
| `template` | Template contents | String, JSON or YAML | No, if `templateUrl` is specified | | `heat_template_version: 2013-05-23\ndescription: LAMP server\n` |
| `templateUrl` | URL of template file | String, HTTP or HTTPS URL | No, if `template` is specified | | `https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml` |
| `parameters` | Arguments to the template, based on the template's parameters | Associative array | No | | `array('server_hostname' => 'web01')` |
| `timeoutMins` | Duration, in minutes, after which stack creation should time out | Integer | Yes | | 5 |

#### Create Stack from Template File

If your template is stored on your local computer as a JSON or YAML file, you
can use it to create a stack as shown below:

```php
$stack = $orchestrationService->createStack(array(
    'name'         => 'simple-lamp-setup',
    'templateUrl'  => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
    'parameters'   => array(
        'server_hostname' => 'web01',
        'image' => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
    ),
    'timeoutMins' => 5
));
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/create-stack-from-template-file.php) ]

#### Create Stack from Template URL
If your template is stored in a remote location accessible via HTTP or HTTPS,
as a JSON or YAML file, you can use it to create a stack as shown below:

```php
$stack = $orchestrationService->stack();
$stack->create(array(
    'name'          => 'simple-lamp-setup',
    'templateUrl'   => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
    'parameters'    => array(
        'server_hostname' => 'web01',
        'image' => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
    ),
    'timeoutMins'   => 5
));
```
[ [Get the executable PHP script for this example](/samples/Orchestration/create-stack-from-template-url.php) ]

### List Stacks
You can list all the stacks that you have created as shown below:

```php
$stacks = $orchestrationService->listStacks();
foreach ($stacks as $stack) {
    /** @var $stack OpenCloud\Orchestration\Resource\Stack **/
}
```
[ [Get the executable PHP script for this example](/samples/Orchestration/list-stacks.php) ]

### Get Stack
You can retrieve a specific stack either by its name OR by its name and ID.

This operation takes the following positional parameters:

| Position | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| 1 | Name of the stack | String | Yes | | `simple-lamp-setup` |
| 2 | ID of the stack | String | No | | `1ded0b2a-36b6-4f7b-9a8b-b45acf3b5619` |

#### Get Stack by Name

```php
$stack = $orchestrationService->getStack('simple-lamp-setup');
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-stack-by-name.php) ]

#### Get Stack by Name and ID

```php
$stack = $orchestrationService->getStack('simple-lamp-setup', '1ded0b2a-36b6-4f7b-9a8b-b45acf3b5619');
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-stack-by-name-and-id.php) ]

### Get Stack Template
You can retrieve the template used to create a stack. Please note that a JSON
string is returned, regardless of whether a JSON or YAML template was used to
create the stack.

```php
$stackTemplate = $stack->getTemplate();
/** @var $stackTemplate string **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-stack-template.php) ]

### Update Stack
You can update a running stack.

This operation takes one parameter, an associative array, with the following keys:

| Name | Description | Data type | Required? | Default value | Example value |
| ---- | ----------- | --------- | --------- | ------------- | ------------- |
| `template` | Template contents | String, JSON or YAML | No, if `templateUrl` is specified | | `heat_template_version: 2013-05-23\ndescription: LAMP server\n` |
| `templateUrl` | URL of template file | String, HTTP or HTTPS URL | No, if `template` is specified | | `https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml` |
| `parameters` | Arguments to the template, based on the template's parameters | Associative array | No | | `array('flavor_id' => 'performance1_1')` |
| `timeoutMins` | Duration, in minutes, after which stack update should time out | Integer | Yes | | 5 |

#### Update Stack from Template File

If your template is stored on your local computer as a JSON or YAML file, you
can use it to update a stack as shown below:

```php
$stack->update(array(
    'template'      => file_get_contents(__DIR__ . '/lamp.yml'),
    'parameters'    => array(
        'server_hostname' => 'web01',
        'image' => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
    ),
    'timeoutMins'   => 5
));
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/update-stack-from-template-file.php) ]

#### Update Stack from Template URL

If your template is stored in a remote location accessible via HTTP or HTTPS,
as a JSON or YAML file, you can use it to update a stack as shown below:

```php
$stack->update(array(
    'templateUrl'   => 'https://raw.githubusercontent.com/rackspace-orchestration-templates/lamp/master/lamp.yaml',
    'parameters'    => array(
        'server_hostname' => 'web01',
        'image' => 'Ubuntu 14.04 LTS (Trusty Tahr) (PVHVM)'
    ),
    'timeoutMins'   => 5
));
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/update-stack-from-template-url.php) ]

### Delete Stack

If you no longer need a stack and all its resources, you can delete it as shown
below:

```php
$stack->delete();
```
[ [Get the executable PHP script for this example](/samples/Orchestration/delete-stack.php) ]

### Abandon Stack

If you want to delete the stack but preserve all its resources, you can abandon
it as shown below:

```php
$abandonStackData = $stack->abandon();
/** @var $abandonStackData string **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/abandon-stack.php) ]

Note that this operation returns data about the abandoned stack as a string. This
data may be used to recreate the stack using the [Adopt Stack](#adopt-stack)
operation.

### Adopt Stack

If you have data from an abandoned stack, you may adopt it and recreate the stack
as shown below:

```php
$stack = $orchestrationService->adoptStack(array(
    'name'           => 'simple-lamp-setup',
    'template'       => file_get_contents(__DIR__ . '/lamp.yml'),
    'adoptStackData' => $abandonStackData,
    'timeoutMins'    => 5
));
/** @var $stack OpenCloud\Orchestration\Resource\Stack **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/adopt-stack.php) ]

## Stack Resources

A stack is made up of zero or more resources. Examples of resources are databases,
load balancers, servers or the software installed on servers.

### List stack Resources

You can list all the resources for a stack as shown below:

```php
$resources = $stack->listResources();
foreach ($resources as $resource) {
    /** @var $resource OpenCloud\Orchestration\Resource\Resource **/
}
```
[ [Get the executable PHP script for this example](/samples/Orchestration/list-stack-resources.php) ]

### Get Stack Resource

You can retrieve a specific resource in a stack using that resource's name, as shown
below:

```php
// Get resource in stack.
$resource = $stack->getResource('load-balancer');
/** @var $resource OpenCloud\Orchestration\Resource\Resource **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-stack-resource.php) ]

### Get Stack Resource Metadata

You can retrieve the metadata for a specific resource in a stack as shown below:

```php
// Get stack resource metadata.
$resourceMetadata = $resource->getMetadata();
/** @var $resourceMetadata \stdClass **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-stack-resource-metadata.php) ]

## Stack Resource Events
Operations on resources within a stack (such as creation of a resource) produce
events.

### List Stack Events
You can list all events for all resources in a stack as shown below:

```php
$stackEvents = $stack->listEvents();
foreach ($stackEvents as $stackEvent) {
    /** @var $stackEvent OpenCloud\Orchestration\Resource\ResourceEvent **/
}
```
[ [Get the executable PHP script for this example](/samples/Orchestration/list-stack-events.php) ]

### List Stack Resource Events
You can list all events for a specific resource in a stack as shown below:

```php
$resourceEvents = $resource->listEvents();
foreach ($resourceEvents as $resourceEvent) {
    /** @var $resourceEvent OpenCloud\Orchestration\Resource\ResourceEvent **/
}
```
[ [Get the executable PHP script for this example](/samples/Orchestration/list-stack-resource-events.php) ]

### Get Stack Resource Event
You can retrieve a specific event for a specific resource in a stack, by using
the resource event's ID, as shown below:

```php
$resourceEvent = $resource->getEvent('c1342a0a-59e6-4413-9af5-07c9cae7d729');
/** @var $resourceEvent OpenCloud\Orchestration\Resource\ResourceEvent **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-stack-resource-event.php) ]

## Resource Types
When defining a template, resource types supported by your Cloud must be used.

### List Resource Types
You can list all supported resource types as shown below:

```php
$resourceTypes = $orchestrationService->listResourceTypes();
foreach ($resourceTypes as $resourceType) {
    /** @var $resourceType OpenCloud\Orchestration\Resource\ResourceType **/
}
```
[ [Get the executable PHP script for this example](/samples/Orchestration/list-resource-types.php) ]

### Get Resource Type
You can retrieve a specific resource type's schema as shown below:

```php
$resourceType = $orchestrationService->getResourceType('OS::Nova::Server');
/** @var $resourceType OpenCloud\Orchestration\Resource\ResourceType **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-resource-type.php) ]

### Get Resource Type Template
You can retrieve a specific resource type's representation as it would appear
in a template, as shown below:

```php
$resourceTypeTemplate = $resourceType->getTemplate();
/** @var $resourceTypeTemplate string **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-resource-type-template.php) ]

## Build Info

### Get Build Info
You can retrieve information about the current Orchestration service build as
shown below:

```php
$buildInfo = $orchestrationService->getBuildInfo();
/** @var $resourceType OpenCloud\Orchestration\Resource\BuildInfo **/
```
[ [Get the executable PHP script for this example](/samples/Orchestration/get-build-info.php) ]
