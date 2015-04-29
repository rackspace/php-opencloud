Reverse DNS
===========


Get PTR record
--------------

PTR records refer to a parent device: either a Cloud Server or a Cloud
Load Balancer with a public virtual IP address. You must supply a fully
formed resource object in order to retrieve either one's PTR record:

.. code:: php

    /** @param $parent OpenCloud\DNS\Resource\HasPtrRecordsInterface */

    $ptr = $service->ptrRecord(array(
        'parent' => $parent
    ));

So, in the above example, the ``$parent`` object could be an instance of
``OpenCloud\Compute\Resource\Server`` or
``OpenCloud\LoadBalancer\Resource\LoadBalancer`` - because they both
implement ``OpenCloud\DNS\Resource\HadPtrRecordsInterface``. Please
consult the `server documentation <../compute>`__ and `load
balancer documentation <../load-balancer>`__ for more
detailed usage instructions.


List PTR records
----------------

.. code:: php

    /** @param $parent OpenCloud\DNS\Resource\HasPtrRecordsInterface */

    $ptrRecords = $service->ptrRecordList($parent);

    foreach ($ptrRecords as $ptrRecord) {

    }


Add PTR record
--------------

.. code:: php

    $parent = $computeService->server('foo-server-id');

    $ptr = $dnsService->ptrRecord(array(
        'parent' => $parent,
        'ttl'    => 3600,
        'name'   => 'example.com',
        'type'   => 'PTR',
        'data'   => '192.0.2.7'
    ));

    $ptr->create();

Here is a table that explains the above attributes:

+-----------+------------------------------------------------------------------------------------+------------+
| Name      | Description                                                                        | Required   |
+===========+====================================================================================+============+
| type      | Specifies the record type as "PTR".                                                | Yes        |
+-----------+------------------------------------------------------------------------------------+------------+
| name      | Specifies the name for the domain or subdomain. Must be a valid domain name.       | Yes        |
+-----------+------------------------------------------------------------------------------------+------------+
| data      | The data field for PTR records must be a valid IPv4 or IPv6 IP address.            | Yes        |
+-----------+------------------------------------------------------------------------------------+------------+
| ttl       | If specified, must be greater than 300. Defaults to 3600 if no TTL is specified.   | No         |
+-----------+------------------------------------------------------------------------------------+------------+
| comment   | If included, its length must be less than or equal to 160 characters.              | No         |
+-----------+------------------------------------------------------------------------------------+------------+


Modify PTR record
-----------------

.. code:: php

  $ptr->update(array(
      'ttl' => $ptr->ttl * 2
  ));


Delete PTR record
-----------------

.. code:: php

  $ptr->delete();
