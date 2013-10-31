## ObjectStore completely refactored

All functionality is documented in detail here. If you need a more in-depth look, most of the source code is commented
and has docblocks for each method.

## Renamed service methods

Factory methods in `OpenCloud\OpenStack` have been upgraded for greater consistency. The full range are:

### OpenStack services

- `objectStoreService`
- `computeService`
- `orchestrationService`
- `volumeService`

### Rackspace services

- `databaseService`
- `loadBalancerService`
- `dnsService`
- `cloudMonitoringService`
- `autoscaleService`
- `queuesService`

The arguments remain the same

## HTTP stuff

- Issuing HTTP requests are now a breeze: you can piggy-back off Guzzle\Http and issue your own requests if you so wish.
 For a full overview, please see the [official documentation](http://guzzlephp.org).

## Misc changes

- URLs are no longer returned as strings, but rather as `Guzzle\Http\Url` objects. To get a string representation, you
 can cast the object as a string: `(string) $url`

- The models of most services have had minor changes to their namespaces - which shouldn't matter if you've used the
convenience methods in the past. So a server, for example, is now `OpenCloud\Compute\Resource\Server`.