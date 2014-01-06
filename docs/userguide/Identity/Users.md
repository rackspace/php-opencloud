# Users

## Intro

A user is a digital representation of a person, system, or service who consumes cloud services. Users have credentials and may be assigned tokens; based on these credentials and tokens, the authentication service validates that incoming requests are being made by the user who claims to be making the request, and that the user has the right to access the requested resources. Users may be directly assigned to a particular tenant and behave as if they are contained within that tenant.

## Setup

User objects are instantiated from the Identity service. For more details, see the [Service](Service.md) docs.

## Useful object properties/methods

Property|Description|Getter|Setter
---|---|---|---
id|The unique ID for this user|`getId()`|`setId()`
username|Username for this user|`getUsername()`|`setUsername()`
email|User's email address|`getEmail()`|`setEmail()`
enabled|Whether or not this user can consume API functionality|`getEnabled()` or `isEnabled()`|`setEnabled()`
password|Either a user-defined string, or an automatically generated one, that provides security when authenticating.|`getPassword()` only valid on creation|`setPassword()` to set local property only. To set password on API (retention), use `updatePassword()`.
defaultRegion|Default region associates a user with a specific regional datacenter. If a default region has been assigned for this user and that user has **NOT** explicitly specified a region when creating a service object, the user will obtain the service from the default region.|`getDefaultRegion()`|`setDefaultRegion()`
domainId|Domain ID associates a user with a specific domain which was assigned when the user was created or updated. A domain establishes an administrative boundary for a customer and a container for a customer's tenants (accounts) and users. Generally, a domainId is the same as the primary tenant id of your cloud account.|`getDomainId()`|`setDomainId()`

## List users

```php
$users = $service->getUsers();

foreach ($users as $user) {
   // ...
}
```

For more information about how to use iterators, see the [documentation](../Iterators.md).

## Get user

There are various ways to get a specific user: by name, ID and email address.

```php
use OpenCloud\Identity\Constants\User as UserConst;

// Get user by name
$user1 = $service->getUser('jamie');

// Get user by ID
$user2 = $service->getUser(123456, UserConst::MODE_ID);

// Get user by email
$user3 = $service->getUser('jamie.hannaford@rackspace.com', UserConst::MODE_EMAIL);
```

## Create user

There are a few things to remember when creating a user:

* This operation is available only to users who hold the `identity:user-admin` role. This admin can create a user who holds the  `identity:default` user role.

* The created user **will** have access to APIs but **will not** have access to the Cloud Control Panel.

* Within an account, a maximum of 100 account users can be added.

* If you attempt to add a user who already exists, an HTTP error 409 results.

The `username` and `email` properties are required for creating a user. Providing a `password` is optional; if omitted, one will be automatically generated and provided in the response.

```php
use Guzzle\Http\Exception\ClientErrorResponseException;

try {
   // execute operation
   $user = $service->createUser(array(
      'username' => 'newUser',
      'email'    => 'foo@bar.com'
   ));
} catch (ClientErrorResponseException $e) {
   // catch 4xx HTTP errors
   echo $e->getResponse()->toString();
}

// show generated password
echo $user->getPassword();
```

## Update user

When updating a user, specify which attribute/property you want to update:

```php
$user->update(array(
   'email' => 'new_email@bar.com'
));
```

### Updating a user password

Updating a user password requires calling a distinct method:
```php
$user->updatePassword('password123');
```

## Delete user

```php
$user->delete();
```

## List credentials

This operation allows you to see your non-password credential types for all authentication methods available.

```php
$creds = $user->getOtherCredentials();
```

## Get user API key

```php
echo $user->getApiKey();
```

## Reset user API key

When resetting an API key, a new one will be automatically generated for you:

```php
$user->resetApiKey();
echo $user->getApiKey();
```