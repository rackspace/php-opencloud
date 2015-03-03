Sharing images
==============

Images can be created and deleted by image producers, updated by image
consumers, and listed by both image producers and image consumers:

+-------------+-----------------+-----------------+
| Operation   | Producer can?   | Consumer can?   |
+=============+=================+=================+
| Created     | Yes             | No              |
+-------------+-----------------+-----------------+
| Deleted     | Yes             | No              |
+-------------+-----------------+-----------------+
| Updated     | No              | Yes             |
+-------------+-----------------+-----------------+
| Listed      | Yes             | Yes             |
+-------------+-----------------+-----------------+

The producer shares an image with the consumer by making the consumer a
*member* of that image. The consumer then accepts or rejects the image
by changing the member status. Once accepted, the image appears in the
consumer's image list.

Typical workflow
----------------

1. The producer posts the availability of specific images on a public
   website.

2. A potential consumer provides the producer with his/her tenant ID and
   email address.

3. The producer `creates a new Image Member <>`__ with the consumer's
   details

4. The producer notifies the consumer via email that the image has been
   shared and provides the image's ID.

5. If the consumer wishes the image to appear in his/her image list, the
   consumer `updates their own Member status <>`__ to ``ACCEPTED``.

Additional notes
~~~~~~~~~~~~~~~~

-  If the consumer subsequently wishes to hide the image, the consumer
   can change their Member status to ``REJECTED``.

-  If the consumer wishes to hide the image, but is open to the
   possibility of being reminded by the producer that the image is
   available, the consumer can change their Member status to
   ``PENDING``.

-  Image producers add or remove image members, but may not modify the
   member status of an image member.

-  Image consumers change their own member status, but may not add or
   remove themselves as an image member.

-  Image consumers can boot from any image shared by the image producer,
   regardless of the member status, as long as the image consumer knows
   the image ID.

Setup
-----

All member operations are executed against an `Image <Images.md>`__, so
you will need to set this up first.

List image members
------------------

This operation is available for both producers and consumers.

.. code:: php

    $members = $image->listMembers();

    foreach ($members as $member) {
        /** @param $member OpenCloud\Image\Resource\Member */
    }

For more information about working with iterators, please see the
`iterators documentation <../Iterators.md>`__.

Create image member
-------------------

This operation is only available for producers.

.. code:: php

    $tenantId = 12345;

    /** @param $response Guzzle\Http\Message\Response */
    $response = $image->createMember($tenantId);

Delete image member
-------------------

This operation is only available for producers.

.. code:: php

    $tenantId = 12345;

    /** @param $member OpenCloud\Image\Resource\Member */
    $member = $image->getMember($tenantId);

    $member->delete();

Update image member status
--------------------------

This operation is only available for consumers.

.. code:: php

    use OpenCloud\Images\Enum\MemberStatus;

    $tenantId = 12345;

    /** @param $member OpenCloud\Image\Resource\Member */
    $member = $image->getMember($tenantId);

    $member->updateStatus(MemberStatus::ACCEPTED);

The acceptable states you may pass in are made available to you through
the constants defined in the ``OpenCloud\Images\Enum\MemberStatus``
class.
