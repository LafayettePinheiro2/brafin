Brafin
======

A Symfony project created on May 16, 2016, 6:28 pm.

To install the application after cloning:

1) give permission to /var directory inside the project

    HTTPDUSER="ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1"

    sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:'whoami':rwX var

    sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:'whoami':rwX var

2) composer install

3) composer update if 

4) php bin/console assets:install

5) php bin/console assetic:dump

6) access on browser localhost/[ProjectDirName]/web/app_dev.php


    php bin/console doctrine:generate:entities AppBundle


    php bin/console doctrine:schema:update --force


    php bin/console cache:clear


    * mkdir web/uploads 

    * give writing permission under this directory to the webserver user, normally www-data.
