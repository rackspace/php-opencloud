# OpenCloud autoloading

OpenCloud uses [Symfony 2's universal autoloader](https://github.com/symfony/symfony/blob/master/src/Symfony/Component/ClassLoader/UniversalClassLoader.php) to manager automatic class instantiation.

When your application references `php-opencloud.php`, we take care of registering the OpenCloud library classes for you. If you take a look at the file, you can see that it registers the root namespace for OpenCloud; thereby allowing all subsequent file includes to happen automatically:

```php
$classLoader = new ClassLoader;
$classLoader->registerNamespaces(array(
    'OpenCloud' => array(__DIR__, __DIR__ . '/../tests')
));
$classLoader->register();
```

The ClassLoader class above utilizes PHP's `spl_autoload_register` [native function](http://php.net/manual/en/function.spl-autoload-register.php), which allows users to specify a custom method for autoloading.

## Using multiple autoloaders

According to the official documentation:

> If there must be multiple autoload functions, spl_autoload_register() allows for this. It effectively creates a queue of autoload functions, and runs through each of them in the order they are defined.

PHP creates the queue for you, on the condition that you have multiple `spl_autoload_register` calls. It will iterate through each custom autoload method and attempt to include the PHP file; if it can't, it will move on to the next autoload method.

This means that to effectively use multiple autoload functions, you must include a conditional statement to make sure the class exists (i.e. allowing PHP to rollover to the next autoload function if the current one cannot locate the file you're looking for).

If your application requires a custom autoloading method, you might want to do something like this:

```php
$includePaths = array();
$includePaths[] = __DIR__ . '/Folder1/Folder2';

spl_autoload_register(function ($class) use ($includePaths) {
    foreach ($includePaths as $path) {
        $file = $path . '/' . strtolower($class) . '.class.php';
        if (is_file($file)) {
              include($file);
        }
    }
});

// Finished with my app's autoloader, so I'll allow OpenCloud to take over...

require '/path/to/lib/php-opencloud.php';

$connection = new \OpenCloud\OpenStack(...);

```
