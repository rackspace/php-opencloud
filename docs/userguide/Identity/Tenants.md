# Tenants

## Intro

A tenant is a container used to group or isolate resources and/or identity objects. Depending on the service operator, a tenant may map to a customer, account, organization, or project.

## Setup

Tenant objects are instantiated from the Identity service. For more details, see the [Service](Service.md) docs.

## List tenants

```php
$tenants = $service->getTenants();

foreach ($tenants as $tenant) {
   // ...
}
```

For more information about how to use iterators, see the [documentation](../Iterators.md).