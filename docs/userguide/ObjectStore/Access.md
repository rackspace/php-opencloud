## Setup

```php
use OpenCloud\Rackspace;

$client = new Rackspace(RACKSPACE_US, array(

));

$service = $client->objectStoreService('cloudFiles');
```

## Temporary URLs

Temporary URLs allow you to create time-limited Internet addresses that allow you to grant access to your Cloud Files
account. Using Temporary URL, you may allow others to retrieve or place objects in your containers - regardless of
whether they're CDN-enabled.

### Set "temporary URL" metadata key

You must set this "secret" value on your account, where it can be used in a global state:

```php
$account = $service->getAccount();
$account->setTempUrlSecret('my_secret');

echo $account->getTempUrlSecret();
```

The string argument of `setTempUrlSecret()` is optional - if left out, the SDK will generate a random hashed secret
for you.

### Create a temporary URL

Once you've set an account secret, you can create a temporary URL for your object. To allow GET access to your object
for 1 minute:

```php
$object->getTemporaryUrl(60, 'GET');
```

To allow PUT access for 1 hour:

```php
$object->getTemporaryUrl(360, 'PUT');
```