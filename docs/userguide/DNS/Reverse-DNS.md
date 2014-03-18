# Reverse DNS

DNS usually determines an IP address associated with a domain name. Reverse DNS is the opposite process: resolving a domain name from an IP address. This is usually achieved with a domain name pointer.

## Get PTR record

PTR records refer to a parent device: either a Cloud Server or a Cloud Load Balancer with a public virtual IP address. You must supply a fully formed resource object in order to retrieve either one's PTR record:

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

Here is a table that explains the above attributes:

Name|Description|Required
---|---|---
type|Specifies the record type as "PTR".|Yes
name|Specifies the name for the domain or subdomain. Must be a valid domain name.|Yes
data|The data field for PTR records must be a valid IPv4 or IPv6 IP address.|Yes
ttl|If specified, must be greater than 300. Defaults to 3600 if no TTL is specified.|No
comment|If included, its length must be less than or equal to 160 characters.|No

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