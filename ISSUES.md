KNOWN ISSUES
============

This is NOT exhaustive, but is a list of things that are known problems
and may or may not be worked on soon depending on their prioritization.

### Constructors used positional parameters

When you issue an OpenStack::Compute() or ObjectStore() request, it
requires three positional parameters: name, region, and URL type.
This is cumbersome, especially when you're trying to rely upon
defaults:

    $comp = $openstack->Compute(NULL, 'ORD');

In many other cases, the SDK uses an array of named parameters:

    $comp = $openstack->Compute(array('region'=>'ORD'));

This has the advantage of simplicity, in that parameters that are not
used can simply be omitted.

*Status:* I'm not sure if we want to change this or not.

### Compute::Server object has differing requirements for flavor and image

When you Create() a new Server, it requires a Flavor object and an
Image() object. However, when you retrieve the Server from the service,
it only returns an ID for the service. This ID should probably be converted
into a full Flavor or Image object, though that would add extra HTTP requests
to each call.

In essence, the Server() object should always have the same makeup, but
I'm not sure we want the performance hit.

Another alternative would be to transform the ID/URL/href into an object
whenever we perform an update or create.

*Status:* Still thinking about what to do here.

### Compute::Server doesn't support ssh keypairs

This is a common extension throughout the OpenStack community, but it's
not supported by Rackspace, so I haven't implemented anything for it yet.

*Status:* Pending review.
