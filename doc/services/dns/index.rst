DNS v1
======

.. include:: ../common/rs-only.sample.rst

DNS service
~~~~~~~~~~~

Now to instantiate the DNS service:

.. code-block:: php

  $service = $client->dnsService();


Operations
----------

.. toctree::

  records
  domains
  limits
  reverse-dns


Glossary
--------

  domain
    A domain is an entity/container of all DNS-related information containing
    one or more records.

  record
    A DNS record belongs to a particular domain and is used to specify
    information about the domain. There are several types of DNS records. Each
    record type contains particular information used to describe that record's
    purpose. Examples include mail exchange (MX) records, which specify the
    mail server for a particular domain, and name server (NS) records, which
    specify the authoritative name servers for a domain.

  subdomain
    Subdomains are domains within a parent domain, and subdomains cannot be
    registered. Subdomains allow you to delegate domains. Subdomains can
    themselves have subdomains, so third-level, fourth-level, fifth-level, and
    deeper levels of nesting are possible.

  pointer records
    DNS usually determines an IP address associated with a domain name.
    Reverse DNS is the opposite process: resolving a domain name from an IP
    address. This is usually achieved with a domain name pointer.


Further Links
-------------

- `Getting Started Guide for the API <http://docs.rackspace.com/cdb/api/v1.0/cdb-getting-started/content/DB_Overview.html>`_
- `API Developer Guide <http://docs.rackspace.com/cdns/api/v1.0/cdns-devguide/content/overview.html>`_
- `API release history <http://docs.rackspace.com/cdns/api/v1.0/cdns-releasenotes/content/doc_change_history.html>`_
