* ``{catalogName}`` is the name of the service as it appears in the service
  catalog. OpenStack users *must* set this value. For Rackspace users, a
  default will be provided if you pass in ``null``.

* ``{region}`` is the region the service will operate in. For Rackspace
  users, you can select one of the following from the :doc:`supported regions page
  </regions>`.

* ``{urlType}`` is the :doc:`type of URL </url-types>` to use, depending on which
  endpoints your catalog provides. If omitted, it will default to the public
  network.
