JSON schemas
============

Schema types
------------

There are currently four types of schema: Images schema, Image schema,
Members schema, and Member schema.

Example response from the API
-----------------------------

A sample response from the API, for an Images schema might be:

.. code-block:: json

    {
        "name": "images",
        "properties": {
            "images": {
                "items": {
                    "type": "array",
                    "name": "image",
                    "properties": {
                        "id": {"type": "string"},
                        "name": {"type": "string"},
                        "visibility": {"enum": ["public", "private"]},
                        "status": {"type": "string"},
                        "protected": {"type": "boolean"},
                        "tags": {
                            "type": "array",
                            "items": {"type": "string"}
                        },
                        "checksum": {"type": "string"},
                        "size": {"type": "integer"},
                        "created_at": {"type": "string"},
                        "updated_at": {"type": "string"},
                        "file": {"type": "string"},
                        "self": {"type": "string"},
                        "schema": {"type": "string"}
                    },
                    "additionalProperties": {"type": "string"},
                    "links": [
                        {"href": "{self}", "rel": "self"},
                        {"href": "{file}", "rel": "enclosure"},
                        {"href": "{schema}", "rel": "describedby"}
                    ]
                }
            },
            "schema": {"type": "string"},
            "next": {"type": "string"},
            "first": {"type": "string"}
        },
        "links": [
            {"href": "{first}", "rel": "first"},
            {"href": "{next}", "rel": "next"},
            {"href": "{schema}", "rel": "describedby"}
        ]
    }

The top-level schema is called ``images``, and contains an array of
links and a properties object. Inside this properties object we see the
structure of this top-level ``images`` object. So we know that it will
take this form:

.. code-block:: json

    {
       "images": [something...]
    }

Within this object, we can see that it contains an array of anonymous
objects, each of which is called ``image`` and has its own set of nested
properties:

.. code-block:: json

    {
        "images": [
            {
                [object 1...]
            },
            {
                [object 2...]
            },
            {
                [object 3...]
            }
        ]
    }

The structure of these nested objects are defined as another schema -
i.e. a *subschema*. We know that each object has an ID property
(string), a name property (string), a visibility property (can either be
``private`` or ``public``), etc.

.. code-block:: json

    {
        "images": [
            {
                "id": "foo",
                "name": "bar",
                "visibility": "private",
                // etc.
            },
            {
                "id": "foo",
                "name": "bar",
                "visibility": "private",
                // etc.
            },
            {
                "id": "foo",
                "name": "bar",
                "visibility": "private",
                // etc.
            }
        ]
    }

Each nested property of a schema is represented by the
``OpenCloud\Image\Resource\Schema\Property`` class.

If you would like to find out more about schemas, Guzzle has good
documentation about `service
descriptions <http://docs.guzzlephp.org/en/latest/webservice-client/guzzle-service-descriptions.html>`__,
which is fairly analogous.

JSON Patch
----------

The Glance API has a unique way of updating certain dynamic resources:
they use JSON Patch method, as outlined in `RFC
6902 <http://tools.ietf.org/html/rfc6902>`__.

Requests need to use the
``application/openstack-images-v2.1-json-patch`` content-type.

In order for the operation to occur, the request entity body needs to
contain a very particular structure:

.. code-block:: json

    [
        {"op": "replace", "path": "/name", "value": "Fedora 17"},
        {"op": "replace", "path": "/tags", "value": ["fedora", "beefy"]}
    ]

* The ``op`` key refers to the type of Operation (see
  ``OpenCloud\Image\Enum\OperationType`` for a full list).

* The ``path`` key is a JSON pointer to the document property you want to
  modify or insert. JSON pointers are defined in `RFC
  6901 <http://tools.ietf.org/html/rfc6901>`__.

* The ``value`` key is the value.

Because this is all handled for you behind the scenes, we will not go
into exhaustive depth about how this operation is handled. You can
browse the source code, consult the various RFCs and the `official
documentation <http://docs.rackspace.com/images/api/v2/ci-devguide/content/patch-method.html>`__
for additional information.
