# Keypairs

## Generate new keypair

This operation creates a new keypair under a provided name; the public key value is automatically generated for you.

```php
$keypair = $service->keypair();

$keypair->create(array(
   'name' => 'jamie_keypair_1'
));

echo $keypair->getPublicKey();
```

## Upload existing keypair

This operation creates a new keypair under a provided name using a provided public key value. This public key will probably exist on your local filesystem, and so provide easy access to your server when uploaded.

```php
$keypair = $service->keypair();

$key = <<<EOT
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQDx8nkQv/zgGgB4rMYmIf+6A4l6Rr+o/6lHBQdW5aYd44bd8JttDCE/F/pNRr0lRE+PiqSPO8nDPHw0010JeMH9gYgnnFlyY3/OcJ02RhIPyyxYpv9FhY+2YiUkpwFOcLImyrxEsYXpD/0d3ac30bNH6Sw9JD9UZHYcpSxsIbECHw== Example public key
EOT;

$keypair->create(array(
   'name'      => 'jamie_macbook',
   'publicKey' => $key
));

```

## List keypairs

To list all existing keypairs:

```php
$keys = $service->listKeypairs();

foreach ($keys as $key) {
   // ...
}
```

For more information about iterators, please see [the docs](../Iterators.md).

## Delete keypairs

To delete a specific keypair:

```php
$keypair->delete();
```

## Creating a server with a keypair

In order to spawn an instance with a saved keypair (allowing you to SSH in without passwords), you create your server
using the same operation as usual, with one extra parameter:

```php
use Guzzle\Http\Exception\BadResponseException;
use OpenCloud\Compute\Constants\Network;

$server = $compute->server();

try {
    $response = $server->create(array(
        'name'     => 'New server',
        'image'    => $ubuntuImage,
        'flavor'   => $twoGbFlavor,
        'networks' => array(
            $compute->network(Network::RAX_PUBLIC),
            $compute->network(Network::RAX_PRIVATE)
        ),
        'keypair' => 'jamie_macbook'
    ));
} catch (BadResponseException $e) {
   // error...
}
```

So, as you can see, you specify the **name** of an existing keypair that you previously created on the API.