#!/bin/sh
today=`date +%Y-%m-%d.%Hh%M`

directory_prod_app=/var/www/kazen-garden/production/nuxt-modern-website
directory_prod_api=/var/www/kazen-garden/production/data-json-schemaorg-format-from-apis

cd $directory_prod_api
node --experimental-json-modules ./api_sylius.js

if [ $? -eq 0 ]
then
    exit 0
else
    exit 1
fi
