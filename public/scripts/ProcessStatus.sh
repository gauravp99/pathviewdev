echo "checking required services and application are running on the server or not?"
#apache2 running or not 
ps cax | grep apache2 > /dev/null
if [ $? -eq 0 ]; then
  echo "\t\tapache is running.\t\t"
else
  echo "apache is not running."
  echo "Statrting the apache."
  service apache2 start
fi

#check if redis is running or not
ps cax | grep redis > /dev/null
if [ $? -eq 0 ]; then
	echo "\t\tredis is running."
else 
  echo "starting redis."
  redis-server --port 6380 --slaveof 127.0.0.1 6379
fi

#check if supervisor is running or not
ps cax | grep supervisor > /dev/nukk
if [ $? -eq 0 ]; then
	echo "\t\tsupervisor is running."
else 
 echo "starting supervisor."
 sudo supervisorctl reread
 sudo supervisorctl update
fi

#check if mysql is running or not
ps cax | grep mysql > /dev/null
if [ $? -eq 0 ]; then 
	echo "\t\tmysql is running."

else
	echo "starting mysql"
	service mysqld start 
	sudo /etc/init.d/mysql start
fi

#check if memcached is running or not
ps cax | grep memcached > /dev/null
if [ $? -eq 0 ]; then 
	echo "\t\tmemcached is running."
else
	echo "starting memcached"
	sudo /etc/init.d/memcached restart
fi
