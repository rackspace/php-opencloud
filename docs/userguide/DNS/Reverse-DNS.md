# Reverse DNS

DNS usually determines an IP address associated with a domain name. Reverse DNS is the opposite process: resolving a domain name from an IP address. This is usually achieved with a domain name pointer.

## Get PTR record

PTR records refer to a parent device: either a Cloud Server (with public network access) or a Cloud Load Balancer. You must supply a fully formed resource object in order to retrieve either one's PTR record:

```php
/** @param $parent OpenCloud\DNS\Resource\HasPtrRecordsInterface */

$ptr = $service->ptrRecord(array(
    'parent' => $parent
));
```

So, in the above example, a `$parent` could be an instance of `OpenCloud\Compute\Resource\Server` or `OpenCloud\LoadBalancer\Resource\LoadBalancer` - because they both implement `OpenCloud\DNS\Resource\HadPtrRecordsInterface`. Please consult the [server documentation](../Compute/Server.md) and [load balancer documentation](../LoadBalancer/USERGUIDE.md) for more detailed usage instructions.

## List PTR records

```php
/** @param $parent OpenCloud\DNS\Resource\HasPtrRecordsInterface */

$ptrRecords = $service->ptrRecordList($parent);

foreach ($ptrRecords as $ptrRecord) {

}
```

Please consult the [iterator documentation](docs/userguide/Iterators.md) for more information about iterators.

## Add PTR record

```php
$parent = $computeService->server('foo-server-id');

$ptr = $dnsService->ptrRecord(array(
    'parent' => $parent,
    'ttl'    => 3600,
    'name'   => 'example.com',
    'type'   => 'PTR',
    'data'   => '192.0.2.7'
));

$ptr->create();
```

## Modify PTR record

```php
$ptr->update(array(
    'ttl' => $ptr->ttl * 2
));
```

## Delete PTR record

```php
$ptr->delete();
```