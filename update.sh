#!/usr/bin/env bash

echo
echo Updating project...
echo

git pull
git submodule init
git submodule update --remote
composer install

echo
echo Clearing cache...
echo

rm -rf ./.cache/compiled/*
rm -rf ./.cache/assets/*

echo
echo Updating version file...
echo

git log --oneline --format=%B -n 1 HEAD | head -n 1 > ./.version
git log --oneline --format="%at" -n 1 HEAD | xargs -I{} date -d @{} +%Y-%m-%d >> ./.version
git rev-parse --short HEAD >> ./.version

echo
echo Fixed access rights...
echo

chown www-data:www-data -R *

echo 
echo New version deployed.


