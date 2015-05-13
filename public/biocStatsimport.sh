#!/bin/sh
#this script is for getting teh stats from bioc web site and upload to pathway database

wget "http://bioconductor.org/packages/stats/bioc/pathview.html"

ipadd=$(grep ">`date +"%B"`" pathview.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | head -1)

downloads=$(grep ">`date +"%B"`" pathview.html | grep -o ">[0-9]*<" | grep -o "[0-9]*" | tail -1)

month=`date +'%B'`

year=`date +'%Y'`

echo "insert into biocstatistics values('$month','$year',$ipadd,$downloads);" |mysql -uroot -ptcs@YASH pathway

rm pathview.html
