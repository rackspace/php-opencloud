# Zones

## Info

A monitoring zone is a location that Rackspace Cloud Monitoring collects data from. Examples of monitoring zones are "US West", "DFW1" or "ORD1". It is an abstraction for a general location from which data is collected.

An "endpoint," also known as a "collector," collects data from the monitoring zone. The endpoint is mapped directly to an individual machine or a virtual machine. A monitoring zone contains many endpoints, all of which will be within the IP address range listed in the response. The opposite is not true, however, as there may be unallocated IP addresses or unrelated machines within that IP address range.

A check references a list of monitoring zones it should be run from.

## Setup
```php
$zoneId = 'mzAAAAA';
$zone = $monitoringService->getMonitoringZone($zoneId);
```

## Attributes

Name|Description|Data type|Method
---|---|---|---|---
country_code|Country Code|String longer than 2 characters|`getCountryCode()`
label|Label|String|`getLabel()`
source_ips|Source IP list|Array|`getSourceIps()`

## List all zones

```php
$zones = $service->getMonitoringZones();
```

Please consult the [iterator doc](docs/userguide/Iterators.md) for more information about iterators.

## Perform a traceroute

```php
$traceroute = $zone->traceroute(array(
    'target' => 'http://test.com',
    'target_resolver' => 'IPv4'
));

// How many hops?
echo count($traceroute);

// What was the first hop's IP?
echo $traceroute[0]->ip;
```