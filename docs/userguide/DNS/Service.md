# DNS Service

To instantiate a Compute service object, you first need to setup a
Rackspace/OpenStack client. To do this, or for more information, please consult
the [Clients documentation](../Clients.md).

You will then need to run:

```php
$service = $client->dnsService();
```