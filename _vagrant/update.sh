#!/usr/bin/env bash

# This script is used to ensure that the framework
# is always at the most recent stable version.

PROJECTFOLDER='khonsa'

cd "/var/www/html/${PROJECTFOLDER}"
sudo git pull