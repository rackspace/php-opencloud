# Cloud Monitoring Service

Initializing the Cloud Monitoring is easy - and can be done in a similar way to all other Rackspace services:

1. Create client and pass in auth details. For more information about creating clients, please consult the [Client documentation](../Clients.md).
2. Use the factory method, specifying additional parameters where necessary:

```php
$service = $client->cloudMonitoringService('cloudMonitoring', 'ORD', 'publicURL');
```

All three parameters are optional - if not specified, it will revert to the service's default values which are:

- Name = `cloudMonitoring`
- Region = `DFW`
- URL type = `publicURL`