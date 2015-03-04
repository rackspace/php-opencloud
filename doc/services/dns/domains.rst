Domains
=======

Get domain
----------

To retrieve a specific domain, you will need the domain's **id**, not
its domain name:

.. code-block:: php

    $domain = $service->domain('{domainId}');


If you are having trouble remembering or accessing the domain ID, you
can do a domain list search for your domain and then access its ID.


List domains
------------

These calls provide a list of all DNS domains manageable by a given
account. The resulting list is flat, and does not break the domains down
hierarchically by subdomain. All representative domains are included in
the list, even if a domain is conceptually a subdomain of another domain
in the list.

.. code-block:: php

    $domains = $service->domainList();

    # Return detailed information for each domain
    $domains = $service->domainList(true);


Filter parameters
~~~~~~~~~~~~~~~~~

You can filter the search by using the ``name`` parameter in a key/value array
supplied as a method argument. For example, to retrieve domains named ``foo.com``,
along with any subdomains like ``bar.foo.com``:

.. code-block:: php

    $hoolaDomains = $service->domainList(array(
        'name' => 'foo.com'
    ));

Filter criteria may consist of:

* Any letter (A-Za-z)
* Numbers (0-9)
* Hyphen ("-")
* 1 to 63 characters

Filter criteria should not include any of the following characters:

  ' + , \| ! " £ $ % & / ( ) = ? ^ \* ç ° § ; : \_ > ] [ @ à, é, ò


Finding a domain ID
~~~~~~~~~~~~~~~~~~~

Once you have a list of domains, to retrieve a domain's ID:

.. code-block:: php

  foreach ($domains as $domain) {
      $id = $domain->id;
  }


List domain changes
-------------------

This call shows all changes to the specified domain since the specified
date/time. To list all available changes for a domain for the current day:

.. code-block:: php

  $changes = $domain->changes();


For more granular control, you can manually define the ``since`` parameter like
so:

.. code-block:: php

  $since = date('c', strtotime('last week'));
  $changes = $domain->changes($since);

Once you have a set of changes, you can iterate over them like so:

.. code-block:: php

  foreach ($changes->changes as $change) {
      printf("Domain: %s\nAction: %s\nTarget: %s", $change->domain, $change->action, $change->targetType);

      foreach ($change->changeDetails as $detail) {
          printf("Details: %s was changed from %s to %s", $detail->field, $detail->oldValue, $detail->newValue);
      }
  }


Create domain
-------------

The first thing you will need to do is instantiate a new object and set the
primary ``A`` record for the DNS domain, like so:

.. code-block:: php

  // get empty object
  $domain = $service->domain();

  // add A record
  $aRecord = $domain->record(array(
      'type' => 'A',
      'name' => 'example.com',
      'data' => '192.0.2.17',
      'ttl'  => 3600
  ));

  $domain->addRecord($aRecord);

You also have the option of adding more types of DNS records such as ``CNAME``,
``MX`` and ``NS`` records. This step is completely optional and depends on
your requirements:

.. code-block:: php

  // add CNAME record
  $cRecord = $domain->record(array(
      'type' => 'CNAME',
      'name' => 'www.example.com',
      'data' => 'example.com',
      'ttl'  => 3600
  ));
  $domain->addRecord($cRecord);

  // add MX record
  $mxRecord = $domain->record(array(
      'type' => 'MX',
      'data' => 'mail.example.com',
      'name' => 'example.com',
      'ttl'  => 3600,
      'priority' => 5
  ));
  $domain->addRecord($mxRecord);

  // add NS record
  $nsRecord = $domain->record(array(
      'type' => 'NS',
      'data' => 'dns1.stabletransit.com',
      'name' => 'example.com',
      'ttl'  => 5400
  ));
  $domain->addRecord($nsRecord);

You can also add sub-domains to your new DNS domain. Again, this is completely
optional:

.. code-block:: php

  $subdomain = $domain->subdomain(array(
      'emailAddress' => 'foo@example.com',
      'name'         => 'dev.example.com',
      'comment'      => 'Dev portal'
  ));
  $domain->addSubdomain($subdomain);

Once you've finished configuring how your DNS domain will work, you're ready
to specify the essential details and send it to the API for creation:

.. code-block:: php

  $domain->create(array(
      'emailAddress' => 'webmaster@example.com',
      'ttl'          => 3600,
      'name'         => 'example.com',
      'comment'      => 'Optional comment'
  ));


Clone domain
------------

This call will duplicate an existing domain under a new name. By default, all
records and, optionally, subdomains are duplicated as well.

The method signature you will need to use is:

.. function:: cloneDomain( $newDomainName[, $subdomains[, $comments[, $email[, $records]]]] )

  Clone a domain

  :param string $newDomainName: The name of the new domain entry
  :param bool $subdomains: Set to ``true`` to clone all the subdomains for this domain
  :param bool $comments: Set to ``true`` to replace occurrences of the reference domain name with the new domain name in comments on the cloned (new) domain.
  :param bool $email: Set to ``true`` to replace occurrences of the reference domain name with the new domain name in data fields (of records) on the cloned (new) domain. Does not affect NS records.
  :param bool $records: Set to ``true`` to replace occurrences of the reference domain name with the new domain name in data fields (of records) on the cloned (new) domain. Does not affect NS records.


For example:

.. code-block:: php

    $asyncResponse = $domain->cloneDomain('new-name.com', true, false, true, false);


Export domain
-------------

This call provides access to the `BIND <http://www.isc.org/downloads/bind/>`_
(Berkeley Internet Name Domain) 9 for the requested domain. This call is for a
single domain only, and as such, does not traverse up or down the domain
hierarchy for details:

.. code-block:: php

    $asyncResponse = $domain->export();

    $body = $asyncResponse->waitFor('COMPLETED');
    echo $body['contents'];


Import domain
-------------

This operation will create a new DNS domain according to a `BIND <http://www.isc.org/downloads/bind/>`_
(Berkeley Internet Name Domain) 9 formatted value.

In order for the BIND value to be considered valid, it needs to adhere to the
following rules:

* Each record starts on a new line and on the first column. If a record will
  not fit on one line, use the BIND\_9 line continuation convention where you put
  a left parenthesis and continue the one record on the next line and put a right
  parenthesis when the record ends. For example:

       example2.net. 3600 IN SOA dns1.stabletransit.com. (sample@rackspace.com. 1308874739 3600 3600 3600 3600)

* The attribute values of a record must be separated by a single blank or tab.
  No other white space characters.

* If there are any NS records, the data field should not be
  ``dns1.stabletransit.com`` or ``dns2.stabletransit.com``. They will result in
  "duplicate record" errors.

For example:

.. code-block:: php

  $bind9Data = <<<EOT

  example.net. 3600 IN SOA dns1.stabletransit.com. sample@rackspace.com. 1308874739 3600 3600 3600 3600
  example.net. 86400 IN A 110.11.12.16
  example.net. 3600 IN MX 5 mail2.example.net.
  www.example.net. 5400 IN CNAME example.net.

  EOT;

  $asyncResponse = $service->import($bind9Data);


Modify domain
-------------

Only the TTL, email address and comment attributes of a domain can be modified.
Records cannot be added, modified, or removed through this API operation - you
will need to use the `add records <records#add-record>`__, `modify records
<records#modify-record>`__ or `remove records <records#delete-record>`__
operations respectively.

.. code-block:: php

  $domain->update(array(
      'ttl'          => ($domain->ttl + 100),
      'emailAddress' => 'new_dev@foo.com'
  ));


Delete domain
-------------

.. code-block:: php

    $domain->delete();
