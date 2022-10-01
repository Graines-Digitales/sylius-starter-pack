#!/bin/sh
today=`date +%Y-%m-%d.%Hh%M`

############################
# ssh connection variables #
############################
host=vps552403.ovh.net
port=22
user=root

#################################
# database connection variables #
#################################
db_prod_host=51.38.237.233      
db_prod_name=kazen_garden_dms
db_prod_user=johanrm    
db_prod_pass=fxf2Lk8H44JU

db_dev_name=dms_kazengarden 
db_dev_user=johanrm
db_dev_pass=mypass

##########################
# project path variables #
##########################
directory_prod_app=/var/www/kazen-garden/production/digital-management-system
directory_prod_backend_public=$directory_prod_app/public

directory_dev_app=/home/www/graines-digitales/kazen-garden/digital-management-system
directory_dev_backend_public=$directory_dev_app/public

#####################################################
# synchronize local resources from remote resources #
#####################################################

if [ -d "$directory_dev_backend_public" ]; 
then
        rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $user@$host:$directory_prod_backend_public/media/image/ $directory_dev_backend_public/media/image/ && \
        rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $user@$host:$directory_prod_backend_public/media/icon/ $directory_dev_backend_public/media/icon/ && \
        rsync -avc --stats --delete --force --ignore-errors --omit-dir-times -e "ssh -p $port" $user@$host:$directory_prod_backend_public/media/cache/ $directory_dev_backend_public/media/cache/ && \
        tput setaf 2 && echo "rsync process is complete" && tput sgr0
else
        read -p "No folder resources found, Do you want continue? (Y/n)" yn
         case $yn in
                [Yy]* ) break;;
                [Nn]* ) exit;;
                * ) ;;
        esac
fi && \



######################################################
# import the local database from the remote database #
######################################################

ssh -f $user@$host -p $port "cd $directory_prod_app && mysqldump --host=$db_prod_host --user=$db_prod_user --password=$db_prod_pass --port=3306 $db_prod_name > mysqldump.sql --no-tablespaces && zip -o mysqldump.zip mysqldump.sql" && \
tput setaf 2 && echo "ssh connexion + mysqldump is complete" && tput sgr0 && \

pid=$! && \
wait $pid && \
tput setaf 2 && echo "Wait process with PID $pid" && tput sgr0 && \

scp -r $user@$host:$directory_prod_app/mysqldump.zip $directory_dev_app/mysqldump.zip && \
tput setaf 2 && echo "scp remote dump is complete" && tput sgr0 && \

unzip -o $directory_dev_app/mysqldump.zip -d $directory_dev_app && \
tput setaf 2 && echo "unzip dump is complete" && tput sgr0 && \

echo "
        CREATE DATABASE IF NOT EXISTS $db_dev_name DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
" | mysql -h localhost -u $db_dev_user -p$db_dev_pass
mysql -h localhost -u $db_dev_user -p$db_dev_pass $db_dev_name < $directory_dev_app/mysqldump.sql && \
tput setaf 2 && echo "import dump in local BDD is complete" && tput sgr0