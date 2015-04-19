FDXP
====

Flight Dispatch webpage project


1. git pull origin master
2. export SYMFONY_ENV=prod
3. php composer.phar install --no-dev --optimize-autoloader
4. php app/console cache:clear --env=prod --no-debug
5. php app/console doctrine:schema:update --dump-sql (check before update)
6. php app/console doctrine:schema:update --force (update)