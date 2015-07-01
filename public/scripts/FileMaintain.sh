#!/bin/bash
cd ../all
users=`echo "select email from users" | mysql -uroot -ptcs@YASH pathway`

printf %s "$users" | while IFS= read -r line
do
if [ $line != 'email' ]
then
directory=`pwd`
file=$directory"/"$line
if [ -d $file ]
then 
file=`du -sm $file | awk '$1 > 100' | awk '{print $2}'`
	if [ ${#file} != 0 ]
	then
	analysisFiles=`ls $file`
	print "$analysisFiles" | while IFS= read -r analFile
	do
	creation_date=`stat $file/$analFile | grep Modify | grep -oh "2015-[0-9][0-9]-[0-9][0-9]"`
	c=`(date -d $creation_date +%s)`
	date=`date +"%Y-%m-%d"`
	d=`(date -d $date +%s)`
	date_diff="$(((($d - $c))/(60*60*24)))"
	
	if [ $date_diff -gt 20 && ${#analFile} != 0 ]
	then
	if [ -d  $file"/"$analFile ]
	then
	echo "update analyses set id=0 where analysis_id=\""$analFile"\"" |mysql -u root -ptcs@YASH pathway
	
	echo $file"/"$analFile  
	else
	echo $file"/"$analFile 
	fi
	fi
	done
	fi
fi 
fi
done
