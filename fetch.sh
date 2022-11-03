#!/bin/sh
today=`date +%Y-%m-%d.%Hh%M`

directory_prod_app=/home/www/graines-digitales/kazen-garden/nuxt3-modern-website
directory_prod_api=/home/www/graines-digitales/kazen-garden/data-json-schemaorg-format-from-apis

cd $directory_prod_api
node --experimental-json-modules $directory_prod_api/api_sylius.js