Tokens
======

Intro
-----

A token is an opaque string that represents an authorization to access
cloud resources. Tokens may be revoked at any time and are valid for a
finite duration.

Setup
-----

Token objects are instantiated from the Identity service. For more
details, see the `Service <Service.md>`__ docs.

Useful object properties/methods
--------------------------------

+------------+-------------------------------------------+----------------------------------------+--------------------+
| Property   | Description                               | Getter                                 | Setter             |
+============+===========================================+========================================+====================+
| id         | The unique ID of the token                | ``getId()``                            | ``setId()``        |
+------------+-------------------------------------------+----------------------------------------+--------------------+
| expires    | Timestamp of when the token will expire   | ``getExpires()`` or ``hasExpired()``   | ``setExpires()``   |
+------------+-------------------------------------------+----------------------------------------+--------------------+

Create token (authenticate)
---------------------------

In order to generate a token, you must pass in the JSON template that is
sent to the API. This is because Rackspace's operation expects a
slightly different entity body than OpenStack Keystone.

Request body for Rackspace's generate token operation:

.. code:: json

    {
        "auth": {
            "RAX-KSKEY:apiKeyCredentials": {
                "username": "foo",
                "apiKey": "aaaaa-bbbbb-ccccc-12345678"
            },
            "tenantId": "1100111"
        }
    }

Request body for Keystone's generate token operation:

.. code:: json

    {
        "auth": {
            "passwordCredentials":{
                "username":"demoauthor",
                "password":"theUsersPassword"
            },
            "tenantId": "12345678"
        }
    }

The only real differences you'll notice is the name of the object key
(``RAX-KSKEY:apiKeyCredentials``/``passwordCredentials``) and the secret
(``apiKey``/``password``). The ``tenantId`` property in both templates
are optional. You can also add ``tenantName`` too.

.. code:: php

    use OpenCloud\Common\Http\Message\Formatter;

    $template = sprintf(
       '{"auth": {"RAX-KSKEY:apiKeyCredentials":{"username": "%s", "apiKey": "%s"}}}',
       'my_username',
       'my_api_key'
    );

    $response = $service->generateToken($template);

    $body = Formatter::decode($response);

    // service catalog
    $catalog = $body->access->serviceCatalog;

    // token
    $token = $body->access->token;

    // user
    $user = $body->access->user;

As you will notice, these variables will be stdClass objects - for fully
fledged functionality, let the client authenticate by itself because it
ends up stocking the necessary models for you.

To see the response body structure, consult the `official
docs <http://docs.rackspace.com/auth/api/v2.0/auth-client-devguide/content/POST_authenticate_v2.0_tokens_Token_Calls.html>`__.

Revoke token (destroy session)
------------------------------

.. code:: php

    $tokenId = '1234567';
    $service->revokeToken($tokenId);

