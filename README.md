FDXP
====

To start development:

1. Git clone this rep
2. Install vagrant
3. vagrant up -> vagrant ssh
4. nano /etc/apache2/sites-available/000-default.conf -> change /var/www/public to /var/www/web 
    -> /usr/bin/sudo service apache2 reload
4. cd /var/www
6. composer install (see box.scotch.io for MySQL details)
7. npm install
8. bower install
9. grunt
10. php app/console doctrine:schema:update --force

To rollout on server:

1. git pull origin master
2. export SYMFONY_ENV=prod
3. php composer.phar install --no-dev --optimize-autoloader
4. php app/console cache:clear --env=prod --no-debug
5. php app/console doctrine:schema:update --dump-sql (check before update)
6. php app/console doctrine:schema:update --force (update)