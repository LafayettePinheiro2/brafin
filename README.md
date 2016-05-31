
Coloca o nome do lado do que ta fazendo pra os 2 não pegar a mesma coisa. E dps apaga quando fizer

O QUE FALTA FAZER - fazer logo



  --(fazendo)  * colocar no servidor rodando direito


    * colocar imagens no editar user e por info no cadastro
        - permitir adicionar imagens apenas no editar, e não ao adicionar um novo usuario - melhor;


    * permitir senha ser em branco no editar user (e ai manter a senha anterior q ele tinha)


    * popular banco de dados direito no servidor


    * ação de usar entrar em contato com user - thiago


    * melhorar página index, explicando melhor como funciona o site antes de mostrar os produtos.

    
    * botao - doar item - fazer setar availability para 0 - thiago


    * arrumar pra só poder pegar um produto quando já tiver postado algum - thiago


    * testar usar a aplicação toda quando tiver completa

    * Deletar usuario -> perguntar se tem certeza


O QUE PODE SER MELHORADO - fazer se der tempo


    * mostrar opção de ordenar produtos por mais recentes, ou já fazer isso direto


    * manipular imagens melhor nos controllers de produto e usuario e usar assert Image


    * checar delete de categorias, produtos, imagens, usuario quando tem dependencia



    * usar mesmo arquivo dos produtos nas 3 páginas que mostra produtos.



Brafin
======

A Symfony project created on May 16, 2016, 6:28 pm.


0) Clone the application, copying the files at /brafin directory, or clone from git 

 - git clone https://github.com/LafayettePinheiro2/brafin.git .

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
