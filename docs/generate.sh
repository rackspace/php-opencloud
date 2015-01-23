#!/bin/sh

# Script to be used by Travis CI builds to generate the php-opencloud SDK API
# reference and publish it to the gh-pages branch of the rackerlabs/php-opencloud
# repository.

SOURCE_DIR=lib
WORK_DIR=build/api
API_DOCS_DIR=docs/api
REPO_REMOTE_URL=https://$GH_TOKEN@github.com/rackspace/php-opencloud

# We want our generated API reference to reflect what is 
# on the master branch. So if we aren't currently on
# the master branch, or we aren't part of a PR targetted
# to the master branch, do nothing.
if [ "$TRAVIS_BRANCH" != "master" ]; then
  exit 0
fi

# Generate the API references
./vendor/bin/apigen generate \
    --source $SOURCE_DIR \
    --destination $WORK_DIR

# Switch the branch to gh-pages
git checkout gh-pages
git pull --commit $REPO_REMOTE_URL gh-pages

if [ $? -ne 0 ]; then
  exit 1
fi

# Commit the generated API references
rm -rf $API_DOCS_DIR
mv $WORK_DIR $API_DOCS_DIR
git add -f $API_DOCS_DIR
git commit -m "Re-generated API documentation"

# Push to the remote gh-pages branch so
# changes show up on php-opencloud.com
git push $REPO_REMOTE_URL gh-pages

git checkout master
