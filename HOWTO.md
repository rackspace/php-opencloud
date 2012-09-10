HOW TO DO STUFF
===============

All these commands assume that you are in the repository root
directory.

## Build the docs

This requires PHPDocumentor to be installed; see http://phpdoc.org

	phpdoc -d lib --extensions="inc" -t docs

I've provided a script to remove the old ones, regenerate them, and add
them back to Github:

    scripts/regen-docs.sh

## Run the unit tests

This requires PHPUnit to be installed; see http://phpunit.de

	phpunit tests/

## Run the smoketest

This will create and delete objects in your account, and you may be charged
for them.

    php smoketest.php

## Run a sample script

Be careful: these can create and delete objects in your account that may result
in charges.

List flavors and images:

    php samples/compute/flavors.php

Filter objects by prefix:

    php samples/objectstore/filter.php

Browse the `samples/` directory for other examples.
