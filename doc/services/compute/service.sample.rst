.. include:: ../common/rs-client.sample.rst

Now, set up the Auto Scale service:

.. code-block:: php

  $service = $client->computeService('{catalogName}', '{region}', '{urlType}');


``{catalogName}`` is the **name** of the service, as it appears in the service
catalog. For Rackspace users, this will default to `cloudServersOpenStack`; for
OpenStack users, you must set your own value since it can depend on your
environment setup.

``{region}`` is the Compute region the service will operate in. For Rackspace
users, you can select one of the following from the `supported regions page`.

``{urlType}`` is the type of URL to use, depending on what endpoints your
catalog provides. For Rackspace, you may use either `internalURL` or `publicURL`.
The former will execute HTTP transactions over the internal Rackspace network,
reducing latency and the overall bandwidth cost - the caveat is that all of your
resources must be in same region. `publicURL`, however, which is the default,
will operate over the public Internet and is to be used for multi-region
installations.
