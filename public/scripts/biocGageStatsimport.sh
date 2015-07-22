#!/bin/sh
#this script is for getting teh stats from bioc web site and upload to pathway database every day morning 2:30

wget "http://bioconductor.org/packages/stats/bioc/gage.html"

ipadd=$(grep ">`date +%B|cut -c1-3`" gage.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | head -1)

downloads=$(grep ">`date +%B|cut -c1-3`" gage.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | tail -1)

month=`date +'%B'`

year=`date +'%Y'`

echo "delete from biocGagestatistic where month = '$month' and year = '$year';"|mysql -uroot -ptcs@YASH pathway
echo "insert into biocGagestatistic values('$month','$year',$ipadd,$downloads);"
echo "insert into biocGagestatistic values('$month','$year',$ipadd,$downloads);" |mysql -uroot -ptcs@YASH pathway

rm gage.html