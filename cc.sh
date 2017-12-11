#!/bin/sh

WEBPATH=/Users/gtvar/www/sa.cloud/www/SA-Cloud

rm -rf $WEBPATH/var/cache/prod/*
rm -rf $WEBPATH/var/cache/dev/*
rm -rf $WEBPATH/var/cache/test/*


rm -rf $WEBPATH/var/admin/cache/prod/*
rm -rf $WEBPATH/var/admin/cache/dev/*
rm -rf $WEBPATH/var/admin/cache/test/*