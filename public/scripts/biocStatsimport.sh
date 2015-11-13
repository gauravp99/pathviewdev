#!/bin/sh
#this script is for getting teh stats from bioc web site and upload to pathway database every day morning 2:30

wget "http://bioconductor.org/packages/stats/bioc/pathview.html"

ipadd=$(grep ">`date +%B|cut -c1-3`" pathview.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | head -1)

downloads=$(grep ">`date +%B|cut -c1-3`" pathview.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | tail -1)

month=`date +'%B'`

year=`date +'%Y'`

echo "delete from biocStatistics where month = '$month' and year = '$year';"|mysql -uroot -pAdminAdmin PathwayWeb
echo "insert into biocStatistics values('$month','$year',$ipadd,$downloads,'','');"
echo "insert into biocStatistics values('$month','$year',$ipadd,$downloads,'','');" |mysql -uroot -pAdminAdmin PathwayWeb

rm pathview.html
