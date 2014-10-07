Contributing to php-opencloud
-----------------------------

Welcome! If you'd like to work on php-opencloud, we appreciate your
efforts. Here are a few general guidelines to follow:

1. Use the `working` branch for your pull requests. Except in the case of
   an emergency hotfix, we will only update `master` with official releases.

2. All code needs to come with unit tests. If you're introducing new code, you
   will need to write new test cases; if you're updating existing code, you will
   need to make sure the methods you're updating are still completely covered.

3. Please abide by PSR code styling.

4. Explaining your pull requests is appreciated. Unless you're fixing a
   minor typographical error, create a description which explains your changes
   and, where relevant, references the existing issue you're hoping to fix.

5. Document your code!

If you submit code, please add your name and email address to the
CONTRIBUTORS file.

Test Instructions
-----------------

### To run unit tests:
```bash
phpunit
```

### To run the full suite of acceptance tests:
1. Make sure your [variables-order](http://www.php.net/manual/en/ini.core.php#ini.variables-order) is set to "EGCRS"
2. Set your *PHP_OpenCloud_USERNAME* and *PHP_OpenCloud_API_KEY* variables
3. Run: ```php tests/OpenCloud/Smoke/Runner.php```

## Conventions

* When working on a `Service` class (e.g. [`OpenCloud\Image\Service`](/lib/OpenCloud/Image/Service.php), name methods like so:

  * Methods that return a single resource, say `Foo`, should be named `getFoo`. For example, [`getImage`](/lib/OpenCloud/Image/Service.php#L67).
  * Methods that return a collection of resources, say `Foo`, should be named `listFoos`. For example, [`listImages`](/lib/OpenCloud/Image/Service.php#L53).
  * Methods that create a new resource, say `Foo`, should be named `createFoo`. For example, [`createEntity`](/lib/OpenCloud/CloudMonitoring/Service.php#L105).