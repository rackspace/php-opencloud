Notes for Developers
====================

1. Most objects are a subclass of `PersistentObject`. Before you add new code
   to handle a specific object, think about whether or not that code can be
   made reusable for other objects. If so, it should probably go in the
   `PersistentObject` class.
2. Most objects are associated with a Service. The Service can be considered
   the object's parent. `PersistentObject` provides two methods: `Service()`
   and `Parent()`. These are synonyms by default. In some cases, however, 
   the parent of an object is not a service, but another object type. For
   example, the parent of `VolumeAttachment` is a `Server`, not a service. 
   In these cases, the `__construct()` method of the child class should
   save the parent object and override the `Parent()` method to return it
   as opposed to the `Service()`. To continue with the `VolumeAttachment`
   example, using the `Service()` to construct a URL results in an incorrect
   Url.