``{catalogName}`` is the **name** of the service, as it appears in the service
catalog. For Rackspace users, a default will be provided if you pass ``null``
in for this argument. For OpenStack users, you cannot do this: you must instead
set your own value since it can depend on your environment setup.

``{region}`` is the Compute region the service will operate in. For Rackspace
users, you can select one of the following from the `supported regions page
</regions>`_.

``{urlType}`` is the type of URL to use, depending on what endpoints your
catalog provides. For Rackspace, you may use either ``internalURL`` or
``publicURL``. The former will execute HTTP transactions over the internal
network configured for your service, possibly reducing latency and the overall
bandwidth cost - the caveat is that all of your resources must be in same region.
``publicURL``, however, which is the default, will operate over the public
Internet and is to be used for multi-region installations.
