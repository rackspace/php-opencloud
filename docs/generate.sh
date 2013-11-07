#!/bin/bash
# (c)2013 Rackspace Hosting. See COPYING for license.


DOC_DIR=docs/api
TEMPLATE=responsive
BIN_FILE=vendor/bin/phpdoc.php

# Make sure PHPDoc is installed
if [ ! -f $BIN_FILE ]; then
    rm composer.lock
    php composer.phar install --dev
fi

if [ ! -d $DOC_DIR ]; then
    mkdir $DOC_DIR
fi

# make sure we're in the root directory
if [ ! -d docs ]; then
    echo "No docs/ directory found; run this script from the top directory"
    exit;
fi

# clean out all the old files
find $DOC_DIR -type f -exec rm {} \;

# regenerate all the docs!
php $BIN_FILE -d lib --extensions="php" -t $DOC_DIR --template $TEMPLATE --force --progressbar