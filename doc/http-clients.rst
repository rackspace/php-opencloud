HTTP Clients
============

Default HTTP headers
--------------------

To set default HTTP headers:

.. code-block:: php

  $client->setDefaultOption('headers/X-Custom-Header', 'FooBar');


User agents
-----------

php-opencloud will send a default ``User-Agent`` header for every HTTP
request, unless a custom value is provided by the end-user. The default
header will be in this format:

  User-Agent: OpenCloud/xxx cURL/yyy PHP/zzz

where ``xxx`` is the current version of the SDK, ``yyy`` is the current
version of cURL, and ``zzz`` is the current PHP version. To override
this default, you must run:

.. code-block:: php

  $client->setUserAgent('MyCustomUserAgent');

which will result in:

  User-Agent: MyCustomUserAgent

If you want to set a *prefix* for the user agent, but retain the default
``User-Agent`` as a suffix, you must run:

.. code-block:: php

  $client->setUserAgent('MyPrefix', true);

which will result in:

  User-Agent: MyPrefix OpenCloud/xxx cURL/yyy PHP/zzz

where ``$client`` is an instance of ``OpenCloud\OpenStack`` or
``OpenCloud\Rackspace``.


Other functionality
-------------------

For a full list of functionality provided by Guzzle, please consult the
`official documentation`_.

.. _official documentation: http://docs.guzzlephp.org/en/latest/http-client/client.html
