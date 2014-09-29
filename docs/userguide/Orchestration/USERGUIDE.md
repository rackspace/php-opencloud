# The Complete User Guide to the Orchestration Service

TODO: Intro

## Concepts

TODO

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

In the example above, you are connecting to the ``DFW`` region of the cloud. Any resources and stacks created with this `$orchestrationService` instance will be created in that cloud region.

## Templates
### Validate Template
#### Validate Template from File
#### Validate Template from URL

## Stacks

### Preview Stack
### Create Stack
#### Create Stack from Template File
#### Create Stack from Template URL
### List Stacks
### Get Stack
#### Get Stack by Name and ID
#### Get Stack by Name
### Get Stack Template
### Update Stack
### Delete Stack
### Abandon Stack
### Adopt Stack

## Stack Resources

### List stack Resources
### Get stack Resource

## Stack Resource Events

### List Stack Events
### List Stack Resource Events
### Get Stack Resource Event

## Resource Types

### List Resource Types
### Get Resource Type
### Get Resource Type Template

## Build Info

### Get Build Info
