Brafin
======

A Symfony project created on May 16, 2016, 6:28 pm.

To install the application after cloning:

1) Give permission to /var directory inside the project

    HTTPDUSER="ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1"

    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:'whoami':rwX var

    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:'whoami':rwX var

2) Execute these commands on the following order, inside the project folder:

        composer install                                            -> install dependencies
        
        composer update         
        
        php bin/console assets:install                              -> install web assets (CSS, js, images)
        
        php bin/console assetic:dump                                -> generate minified and optimized assets
        
        php bin/console doctrine:schema:update --force              -> generate schema database through annotation
        
        php bin/console doctrine:generate:entities AppBundle        -> generate entities based on schema
        
        php bin/console cache:clear
        
        mkdir web/uploads 
        
        * give writing permission under this directory to the webserver user, normally www-data.


3) Access on browser http://localhost/[ProjectDirName]/web/app_dev.php
