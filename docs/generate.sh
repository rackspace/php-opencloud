#!/bin/bash
# (c)2013 Rackspace Hosting. See COPYING for license.

DOC_DIR=docs/api
LIB_DIR=lib
BIN_FILE=vendor/bin/apigen.php

if [ ! -f $BIN_FILE ]; then
    rm composer.lock
    php composer.phar require apigen/apigen:dev-master --dev
fi

if [ ! -d $DOC_DIR ]; then
    mkdir $DOC_DIR
fi

if [ ! -d docs ]; then
    echo "No docs/ directory found; run this script from the top directory"
    exit;
fi

rm -rf DOCS_DIR

# regenerate all the docs!
php $BIN_FILE -s $LIB_DIR -d $DOC_DIR --title="PHP OpenCloud API" --groups="namespaces" --download --progressbar