#!/bin/sh
today=`date +%Y-%m-%d.%Hh%M`

############################
# ssh connection variables #
############################
host=6c222ed.online-server.cloud
port=22
user=root

##########################
# project path variables #
##########################
directory_prod_app=/var/www/vhosts/kazengarden.com/digital-management-system
directory_dev_app=/home/www/graines-digitales/kazen-garden/digital-management-system

##########################
##### commands deploy ####
##########################


# commandSiteSyncPreprod="sh ./site-sync-prod.sh" 



##########################
########## RUN ###########
##########################
# tput setaf 2 && echo "ssh connexion && mkdir -p public/media/image" && tput sgr0 && \

ssh -f $user@$host -p $port "cd $directory_prod_app && mkdir -p public/media/image" && \

ssh -f $user@$host -p $port "cd $directory_prod_app && git checkout master && git pull origin master" && \

rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $directory_dev_app/src/EventListener/AdminMenuListener.php $user@$host:$directory_prod_app/src/EventListener/AdminMenuListener.php && \
rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $directory_dev_app/assets/images $user@$host:$directory_prod_app/assets/ && \
rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $directory_dev_app/config/project.yaml $user@$host:$directory_prod_app/config/project.yaml && \
rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $directory_dev_app/content/ $user@$host:$directory_prod_app/content/ && \
rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $directory_dev_app/assets/app/styles/_variables.scss $user@$host:$directory_prod_app/assets/app/styles/_variables.scss && \
rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $directory_dev_app/public/media $user@$host:$directory_prod_app/public/ && \


# ssh -f $user@$host -p $port "cd $directory_prod_app && git pull origin master" && \

exit 0
# ssh -f $user@$host -p $port "cd $directory_prod_app && composer install" && \
# ssh -f $user@$host -p $port "cd $directory_prod_app && yarn install" && \
# ssh -f $user@$host -p $port "cd $directory_prod_app && ./bin/console cache:clear --env=prod" && \
# ssh -f $user@$host -p $port "cd $directory_prod_app && chmod -R 777 $directory_app/var/cache/ $directory_app/var/log/" && \


## execute command ##
# $commandSiteSync
# ## check exit code ##
# if [ $? -eq 0 ]
# then
#     echo "Command was successful : $commandSiteSync"
# else
#     echo "Command failed : $commandSiteSync"
#     exit 1
# fi
# exit 1