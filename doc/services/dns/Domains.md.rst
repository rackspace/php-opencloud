Domains
=======

A domain is an entity/container of all DNS-related information
containing one or more records.

Setup
-----

Limit methods will be called on the DNS service, an instance of
``OpenCloud\DNS\Service``. Please see the `DNS service <Service.md>`__
documentation for setup instructions.

Get domain
----------

To retrieve a specific domain, you will need the domain's **id**, not
its domain name.

.. code:: php

    $domain = $service->domain(12345);

If you are having trouble remembering or accessing the domain ID, you
can do a domain list search for your domain and then access its ID.

List domains
------------

These calls provide a list of all DNS domains manageable by a given
account. The resulting list is flat, and does not break the domains down
hierarchically by subdomain. All representative domains are included in
the list, even if a domain is conceptually a subdomain of another domain
in the list.

.. code:: php

    $domains = $service->domainList();

    # Return detailed information for each domain
    $domains = $service->domainList(true);

Please consult the `iterator
documentation </docs/userguide/Iterators.md>`__ for more information
about iterators.

Filter parameters
~~~~~~~~~~~~~~~~~

You can filter the aforementioned search by using the ``name`` parameter
in a key/value array supplied as a method argument. For example,
providing ``array('name' => 'hoola.com')`` will return hoola.com and
similar names such as main.hoola.com and sub.hoola.com.

.. code:: php

    $hoolaDomains = $service->domainList(array(
        'name' => 'hoola.com'
    ));

Filter criteria may consist of:

-  Any letter (A-Za-z)
-  Numbers (0-9)
-  Hyphen ("-")
-  1 to 63 characters

Filter criteria should not include any of the following characters:

    ' + , \| ! " £ $ % & / ( ) = ? ^ \* ç ° § ; : \_ > ] [ @ à, é, ò

Finding a domain ID
~~~~~~~~~~~~~~~~~~~

If you know a domain's name, but not its unique identifier, you can do
this:

.. code:: php

    $domains = $service->domainList(array(
        'name' => 'foo.com'
    ));

    foreach ($domains as $domain) {
        $id = $domain->id;
    }

List domain changes
-------------------

This call shows all changes to the specified domain since the specified
date/time. The since parameter is optional and defaults to midnight of
the current day.

.. code:: php

    $changes = $domain->changes();

    # Changes since last week
    $since = date('c', strtotime('last week'));
    $changes = $domain->changes($since);

    foreach ($changes->changes as $change) {
        printf("Domain: %s\nAction: %s\nTarget: %s", $change->domain, $change->action, $change->targetType);

        foreach ($change->changeDetails as $detail) {
            printf("Details: %s was changed from %s to %s", $detail->field, $detail->oldValue, $detail->newValue);
        }
    }

Export domain
-------------

This call provides the BIND (Berkeley Internet Name Domain) 9 formatted
contents of the requested domain. This call is for a single domain only,
and as such, does not traverse up or down the domain hierarchy for
details (that is, no subdomain information is provided).

.. code:: php

    $asyncResponse = $domain->export();
    $body = $asyncResponse->waitFor('COMPLETED');
    echo $body['contents'];

Create domain
-------------

A domain is composed of DNS records (e.g. ``A``, ``CNAME`` or ``MX``
records) and an optional list of sub-domains. You will need to specify
these before creating the domain itself:

.. code:: php

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

    // add optional C record
    $cRecord = $domain->record(array(
        'type' => 'CNAME',
        'name' => 'www.example.com',
        'data' => 'example.com',
        'ttl'  => 3600
    ));
    $domain->addRecord($cRecord);

    // add optional MX record
    $mxRecord = $domain->record(array(
        'type' => 'MX',
        'data' => 'mail.example.com',
        'name' => 'example.com',
        'ttl'  => 3600,
        'priority' => 5
    ));
    $domain->addRecord($mxRecord);

    // add optional NS records
    $nsRecord1 = $domain->record(array(
        'type' => 'NS',
        'data' => 'dns1.stabletransit.com',
        'name' => 'example.com',
        'ttl'  => 5400
    ));
    $domain->addRecord($nsRecord1);

    $nsRecord2 = $domain->record(array(
        'type' => 'NS',
        'data' => 'dns2.stabletransit.com',
        'name' => 'example.com',
        'ttl'  => 5400
    ));
    $domain->addRecord($nsRecord2);

    // add optional subdomains
    $sub1 = $domain->subdomain(array(
        'emailAddress' => 'foo@example.com',
        'name'         => 'dev.example.com',
        'comment'      => 'Dev portal'
    ));
    $domain->addSubdomain($sub1);

    // send to API
    $domain->create(array(
        'emailAddress' => 'webmaster@example.com',
        'ttl'          => 3600,
        'name'         => 'example.com',
        'comment'      => 'Optional comment'
    ));

Clone domain
------------

This call will duplicate a single existing domain configuration with a
new domain name for the specified Cloud account. By default, all records
and, optionally, subdomain(s) are duplicated as well.

The method signature you will need to use is:

.. code:: php

    cloneDomain ( string $newDomainName [, bool $subdomains [, bool $comments [, bool $email [, bool $records ]]]] )

+----------------------+--------------+------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Name                 | Data type    | Default    | Description                                                                                                                                                                        |
+======================+==============+============+====================================================================================================================================================================================+
| ``$newDomainName``   | ``string``   | -          | The new name for your cloned domain                                                                                                                                                |
+----------------------+--------------+------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``$subdomains``      | ``bool``     | ``true``   | Set to ``TRUE`` to clone all the subdomains for this domain                                                                                                                        |
+----------------------+--------------+------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``$comments``        | ``bool``     | ``true``   | Set to ``TRUE`` to replace occurrences of the reference domain name with the new domain name in comments on the cloned (new) domain.                                               |
+----------------------+--------------+------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| ``$email``           | ``bool``     | ``true``   | Set to ``TRUE`` to replace occurrences of the reference domain name with the new domain name in data fields (of records) on the cloned (new) domain. Does not affect NS records.   |
+----------------------+--------------+------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+

For example:

.. code:: php

    $asyncResponse = $domain->cloneDomain('new-name.com', true);

Import domain
-------------

This call provisions a new DNS domain under the account specified by the
BIND 9 formatted file configuration contents defined in the request
object.

You will need to ensure that the BIND 9 formatted file configuration
contents are valid by adhering to the following rules:

-  Each record starts on a new line and on the first column. If a record
   will not fit on one line, use the BIND\_9 line continuation
   convention where you put a left parenthesis and continue the one
   record on the next line and put a right parenthesis when the record
   ends. For example,

       example2.net. 3600 IN SOA dns1.stabletransit.com. (
       sample@rackspace.com. 1308874739 3600 3600 3600 3600)

-  The attribute values of a record must be separated by a single blank
   or tab. No other white space characters.

-  If there are any NS records, the data field should not be
   dns1.stabletransit.com or dns2.stabletransit.com. They will result in
   "duplicate record" errors.

For example:

.. code:: php

    $bind9Data = <<<EOT
    \nexample.net. 3600 IN SOA dns1.stabletransit.com. sample@rackspace.com. 1308874739 3600 3600 3600 3600\nexample.net. 86400 IN A 110.11.12.16\nexample.net. 3600 IN MX 5 mail2.example.net.\nwww.example.net. 5400 IN CNAME example.net.\n
    EOT;

    $asyncResponse = $service->import($bind9Data);

Modify domain
-------------

This call modifies DNS domain(s) attributes only. Only the TTL, email
address and comment attributes of a domain can be modified. Records
cannot be added, modified, or removed through this API operation - you
will need to use the `add
records </docs/userguide/DNS/Records.md#add-record>`__, `modify
records </docs/userguide/DNS/Records.md#modify-record>`__ or `remove
records </docs/userguide/DNS/Records.md#delete-record>`__ operations
respectively.

.. code:: php

    $domain->update(array(
        'ttl'          => ($domain->ttl + 100),
        'emailAddress' => 'new_dev@foo.com'
    ));

Remove domain
-------------

.. code:: php

    $domain->delete();

