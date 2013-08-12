Debugging OpenCloud
=========

The OpenCloud library contains various debug calls that output messages during particular algorithm executions. By default, this output is disabled (to save unnecessary text on your console), but you can very easily enable it in two ways:

1. Set the `RAXSDK_DEBUG` global to `true` in `Globals.php`. This will show every log message through the application;

2. Setting the debug options for a particular service object. By calling `setDebug(true)` on the object, you will enable debug output for all service methods called thereafter. (**Note:** a few service objects contain debug traces during the constructor - which we're in the process of moving out).

The global method helps you investigate a whole bunch of objects you're working with, and the `setDebug()` method is a more granular approach for the vast majority of cases where you'll want to understand what a particular object does. The only condition for `setDebug()` is that because its set in `Common\Base.php`, the object must be a concrete instantiation of this Base class (i.e. a Service object).

If you want to investigate a random, arbitrary object - you can also now do this through a new Debug feature:

```php
use OpenCloud\Base\Debug;

Debug::investigate($randomObject);
```

which basically outputs its characteristics (class name, parent class, class methods, and class properties -- for now).