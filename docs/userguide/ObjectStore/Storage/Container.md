# 1. List containers

## 1.1 Return a list of containers

```php
$containerList = $service->listContainers();

while ($container = $containerList->next()) {
    // ... do stuff
}
```

Container names are sorted based on a binary comparison, a single built-in collating sequence that compares string
data using SQLite's memcmp() function, regardless of text encoding.

The list is limited to 10,000 containers at a time. See 1.3 for ways to limit and navigate this list.

## 1.2 Return a formatted list of containers

Currently, the SDK only supports JSON-formatted responses.

## 1.3 Controlling a large list of containers

You may limit and control this list of results by using the `marker` and `end_marker` parameters. The former parameter
(`marker`) tells the API where to begin the list, and the latter (`end_marker`) tells it where to end the list. You may
use either of them independently or together. You may also use the `limit` parameter to fix the number of containers
returned.

To list a set of containers between two fixed points:

```php
$someContainers = $service->listContainers(array(
    'marker'     => 'container_55',
    'end_marker' => 'container_2001'
));
```

Or to return a limited set:

```php
$someContainers = $service->listContainers(array('limit' => 560));
```

# Get container



# Create container

# Delete container

# Create or update container metadata

# Container quotas

# Access log delivery