# Records

A DNS record belongs to a particular domain and is used to specify information about the domain.

There are several types of DNS records. Examples include mail exchange (MX) records, which specify the mail server for a particular domain, and name server (NS) records, which specify the authoritative name servers for a domain.

It is represented by the `OpenCloud\DNS\Resource\Record` class. Records belong to a [Domain](Domains.md).

## Get record

In order to retrieve details for a specific DNS record, you will need its **id**:

```php
$record = $domain->record('NS-1234567');
```

If you do not have this ID at your disposal, you can traverse the record collection and do a string comparison (detailed below).

## List records

This call lists all records configured for the specified domain.

```php
$records = $domain->recordList();

foreach ($records as $record) {
    printf("Record name: %s, ID: %s, TTL: %s\n", $record->name, $record->id, $record->ttl);
}
```

Please consult the [iterator documentation](docs/userguide/Iterators.md) for more information about iterators.

### Query parameters

You can pass in an array of query parameters for greater control over your search:

Name|Data type|Default|Description
---|---|---|---
`type`|`string`|The record type
`name`|`string`|The record name
`data`|`string`|Data for this record

### Find a record ID from its name

For example:

```php
$records = $domain->recordList(array(
    'name' => 'imap.example.com',
    'type' => 'MX'
));

foreach ($records as $record) {
    $recordId = $record->id;
}
```

## Add record

This call adds a new record to the specified domain:

```php
$record = $domain->record(array(
    'type' => 'A',
    'name' => 'example.com',
    'data' => '192.0.2.17',
    'ttl'  => 3600
));

$record->create();
```

Please be aware that records that are added with a different hostname than the parent domain might fail silently.

## Modify record

```php
$record = $domain->record(123456);
$record->ttl -= 100;
$record->update();
```

## Delete record

```php
$record->delete();
```