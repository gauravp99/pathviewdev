#!/bin/bash
#This script is for getting the stats from bioc gage web site and upload to pathway database every day morning 2:30
#Author: Gaurav P
#Script usage: Run the script from public directory with public path as the parameter ./scripts/biocGageStatsimport.sh 

##Check for the environment file. 
public_path=$1
date=`date +"%Y-%m-%d-%H:%M:%S"`
echo "Starting execution of `basename $0` at $date"
if [ ! -f $public_path/../.env ]
then
   echo "The environment file doesn't exist.. Exiting !!"
   exit 1
fi

source $public_path/../.env
if [ -z $DB_USERNAME ] || [ -z $DB_PASSWORD ] || [ -z $DB_DATABASE ]
then
   echo "Database information not found. Exiting ...!!"
   exit 1
fi
MYSQL="mysql -u$DB_USERNAME -p$DB_PASSWORD $DB_DATABASE"

wget -q  "https://bioconductor.org/packages/stats/bioc/gage/gage_stats.tab"

if [ $? -ne 0 ]
then
  echo "Error will fetching the stats from bioc gage website. Exiting..."
  exit 1
fi

files=('gage_stats.tab')
if [ -f gage_stats.tab ]
then
   for file in "${files[@]}"
   do
      tail -n +2 $file |grep -v all |  while read line
      do
         year=`echo $line | tr -s " "  | awk -F " " '{print $1}'`
         month=`echo $line| tr -s " "  | awk -F " " '{print $2}'`
         ipadd=`echo $line | tr -s " "  | awk -F " " '{print $3}'`
         downloads=`echo $line | tr -s " "  | awk -F " " '{print $4}'`
         #echo $year,$month,$ip,$downloads
         if [ $downloads -ne 0 ]
         then
           echo "delete from biocGagestatistic where month = '$month' and year = '$year';"|$MYSQL
           echo "insert into biocGagestatistic values('$month','$year',$ipadd,$downloads);" |$MYSQL
         fi
      done
   done
   rm gage_stats.tab
else
   echo "Bioc Gage Stats file doesn't exist ..Exiting"
   exit 1
fi
