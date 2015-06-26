###Pathway Web application

Pathview maps, integrates and renders a wide variety of biological data on relevant pathway graphs.
Pathview Web provides easy interactive access, and generates high quality,
hyperlinked graphs. Pathview package is written in R/Bioconductor, this web interface is built on PHP with Laravel Framework and R.

You can find each file details at the following link in google docs: [Files Explanation doc](https://docs.google.com/spreadsheets/d/1akyqXw25bGbwlS9Mb-cmfaIZHfr5bdKTv0QPjCvbEew/edit?usp=sharing)

##Pathview web installation/Deployment procedure:
-------------------------------------------------


#1. Mysql client and server installation:
----------------------------------------

`sudo apt-get install mysql-server mysql-client`

You will be asked to provide a password for the MySQL root user - this password is valid for the user root@localhost as well as root@server1.example.com, so we don't have to specify a MySQL root password manually later on:

New password for the MySQL "root" user: <-- yourrootsqlpassword
Repeat password for the MySQL "root" user: <-- yourrootsqlpassword


#2. Database creation and import data:
--------------------------------------

Open mysql database and create database named pathway and import the data from dump file dump.sql at location Pathway/public/dump.sql

To import open the mysql command prompt and copy paste the following
__command__:
`mysql -u username -p pathway < Pathway/public/dump.sql`

To export the database into a file from existing database:
__command__:
`mysqldump -u username -p pathway > dump.sql`

#3. R installation:
-------------------

######3.1. Required R version 3.1.2 you can download R version from "http://cran.r-project.org/src/base/R-3/R-3.1.2.tar.gz"

To install a specific version of R copy and paste following commands:
__command__:
`wget http://cran.r-project.org/src/base/R-3/R-3.1.2.tar.gz`
`tar xvfz R-3.1.2.tar.gz`
`cd R-3.1.2`
`./configure`
`make`
`sudo make install`

######3.2. Bioc Package Required Version (3.0)
Bioc Package Downaloat and installation:
__command__:
`source("http://bioconductor.org/biocLite.R")
biocLite()`

######3.3. Pathview required Version(1.6)

Pathview Package installation:
__command__:
`source("http://bioconductor.org/biocLite.R")
biocLite("pathview")`

#4. Apache2 installation:
-------------------------

Check if the apache installed on the server if not install apache2 running the command
__command__:
`sudo apt-get install apache2`


#5. PHP installation:
---------------------

Check if the PHP installed on the server if not install it required PHP version >5.6
__command__:
`sudo apt-get install php5 libapache2-mod-php5`


#6. Install PHP my adming to have the UI control over the database :
-------------------------------------------------------------------
__command__:
`sudo apt-get install phpmyadmin`

You will see the following questions:

Web server to reconfigure automatically: <-- apache2
Configure database for phpmyadmin with dbconfig-common? <-- No 

you can access the database from  http://<ip address>/phpmyadmin/

#7. Install composer (A dependency manager for the PHP):
-------------------------------------------------------

if curl doesnt exist install curl first then enter the below command install CURL "sudo apt-get install curl"

__Command:__
`curl -sS https://getcomposer.org/installer | php `

rename composer.phar to composer copy it to /usr/bin and update the PATH variabl to the file location to use composer command from any place

#8. Install Laravel 5.0 PHP framework for WEB:
---------------------------------------------
__Command:__
`composer global require "laravel/installer=~1.1"`

Make sure to place the ~/.composer/vendor/bin directory in your PATH so the laravel executable can be located by your system.

#9. Copy the Project code to the /var/www/ folder on the server:
---------------------------------------------------------------

#10. open file .env under the Pathway directory update databsae details and mail details:
----------------------------------------------------------------------------------------

typical settings:
`
APP_ENV=prod  ## for production for local version have value = local

APP_DEBUG=false ## for production no need to show the error occured location to debug change it to true

APP_KEY=ALNqxwwt8hH6qh78zB4NskH1hcoiZEnz ## key used by the application for encryption of the cookies and session variables

DB_HOST=localhost ## url of the database if the database is installed on the server

DB_DATABASE=pathway ## name of the databse created

DB_USERNAME=root ## user name

DB_PASSWORD=***** ## password

CACHE_DRIVER=file

SESSION_DRIVER=file

QUEUE_DRIVER=sync

MAIL_DRIVER=smtp  

MAIL_HOST=smtp.gmail.com

MAIL_PORT=465

MAIL_USERNAME=byeshvant@gmail.com

MAIL_PASSWORD=*****
`
#11. unable the mod_rewrite option of apache2 web server by running the following command
__Command:__
`a2enmod rewrite`

restrat apache2  " command : sudo /etc/init.d/apache2 restart"

#12. Copy apache configuration 000-default.conf to laravel.conf
command : cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/laravel.conf 

add following lines in laravel.conf

DocumentRoot /var/www/Pathway/public

<Directory /var/www/Pathway/public>

 Options Indexes FollowSymLinks MultiViews

 AllowOverride All

 Order allow,deny

 allow from all

</Directory>

#13. Restart apache2

__Command:__
 sudo /etc/init.d/apache2 restart

#14. try accessing the url/ip address


##References:

1. Laravel Installation:
>[Official Laravel Website ](http://laravel.com/docs/5.0/installation)

2. Composer Installation:
>[Composer website](https://getcomposer.org/download/)

3. Install Laravel application on Apache2 server
>[Apache2 Laravel Installation Guide](http://ulyssesonline.com/2014/07/24/install-laravel-4-2-on-ubuntu-server-14-04-lts/)