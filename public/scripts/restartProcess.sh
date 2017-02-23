
echo "restarting redis server"
#sudo redis-server --port 6380 --slaveof 127.0.0.1 6379
sudo service redis-server restart

echo "restarting supervisor"
sudo service supervisor stop

sudo service supervisor start

echo "restarting memcached"
sudo /etc/init.d/memcached restart

echo "restarting mysql"
sudo /etc/init.d/mysql restart

echo "restarting apache"
sudo service apache2 restart

echo "Clearing cache "
cd /var/www/Pathway && php artisan cache:clear
