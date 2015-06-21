#!/bin/sh
#this script is for getting teh stats from bioc web site and upload to pathway database every day morning 2:30
echo "select * from analyses_errors where DATE(created_at) = CURRENT_DATE;"|mysql -uroot -ptcs@YASH pathway;
//send email code goes here to the admin every day on 11:00 PM

