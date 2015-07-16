#!/bin/bash
#!/bin/sh
# this script deletes files of the user when they exceed the limit given to them it deletes files upto a point when the size limit is upto 50 MB
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
#filesize=`ls -s --block-size=1048576 $file | cut -d ' ' -f1`
#ls -s --block-size=1048576 $file | cut -d ' ' -f1
file=`du -sm $file | awk '$1 > 98' |awk '{print $2}' ` 

if [ ${#file} != 0 ]
	then
	echo $file
	analysisFiles=`ls -rt $file | cut -d ' ' -f1`
	
	printf "$analysisFiles" | while IFS= read -r analFile
	do
	if [ ${#analFile} != 0 ]
	then
	
	currsize=`du -sm $file | grep -o "^\S\+" `
	echo $currsize
	if [ $currsize -lt 51 ]
	then
	echo $file "size reached to 50 "
	break
	fi
	echo "delelting" $analFile
	rm -rf $file"/"$analFile
	if [ $? -eq 0 ]
	then
	echo "update analyses set id=0 where analysis_id=\""$analFile"\"" |mysql -u root -ptcs@YASH pathway
	fi
	fi
	done
	fi
fi 
fi
done
