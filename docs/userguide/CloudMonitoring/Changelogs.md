# Changelogs

## Info

The monitoring service records changelogs for alarm statuses. Changelogs are accessible as a Time Series Collection. By default the API queries the last 7 days of changelog information.

## Working with Changelogs
```php
$changelog = $service->getChangelog();

foreach ($changelog as $item) {
   $entity = $item->getEntityId();
}
```