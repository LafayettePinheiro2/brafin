Brafin
======

A Symfony project created on May 16, 2016, 6:28 pm.


0) Clone the application, copying the files at /brafin directory, or clone from git 

 - git clone https://github.com/LafayettePinheiro2/brafin.git .

To install the application after cloning:



1) Set your database parameters at app/config/parameters.yml file.


2) Give permission to /var directory inside the project

    HTTPDUSER="ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1"

    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:'whoami':rwX var

    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:'whoami':rwX var

3) Execute these commands on the following order, inside the project folder

        composer install                                            -> install dependencies
        
        composer update         -> this two composer commands are very important to everything work
        
        php bin/console assets:install                              -> install web assets (CSS, js, images)
        
        php bin/console assetic:dump                                -> generate minified and optimized assets
        
        php bin/console doctrine:schema:update --force              -> generate schema database through annotation
        
        php bin/console doctrine:generate:entities AppBundle        -> generate entities based on SQL schema
        
        php bin/console cache:clear
        
        mkdir web/uploads 
        
        * give writing permission under this directory to the webserver user, normally www-data (for example sudo chown -R www-data www-data web/uploads).



	3.1) access on your browser

	http://localhost/[ProjectDirName]/web/config.php

	To test if your webserver and the framework are well configured. If not, follow the tips to solve it.


4) Access on browser http://localhost/[ProjectDirName]/web/app.php and use the application.





Obs: The first admin user has to be setted manually, setting the field roles at user database table as ROLE_ADMIN. By default each new user 
is setted as ROLE_USER, the normal user. And ROLE_ADMIN user has more privileges and can set other users as admins.


















