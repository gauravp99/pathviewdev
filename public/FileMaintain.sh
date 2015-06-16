#!/bin/sh
# this script automatically deletes file which are older than 30 days
cd all
users=`echo "select email from users" | mysql -uroot -ptcs@YASH pathway`
j=0
printf %s "$users" | while IFS= read -r line
do
   if [ $line != 'email' ]
   	then
   	if [  -d $line ]
   		then
   	du -sh $line
   	#du -sm * | awk '$1 > 100'| awk '{print $2}' # to check files having space greater than 100 MB
	find $line -type f -mtime +30 #-exec rm {} \;
	     fi
	fi
done

