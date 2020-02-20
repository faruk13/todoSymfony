todoSymphony
=======
A simple user based todo using Symfony framework.

### Features
* A user can register and login
* Can create todos sorted by latest added
* Can delete todos
* A user cannot access other users' todo list or profile page.


## Database integration:

    php bin/console doctrine:schema:validate
    php bin/console doctrine:schema:update --force

### Check registered routes:
    php bin/console debug:router

## Run using:
    php bin/console server:run

## Test using
    ./vendor/bin/simple-phpunit tests/AppBundle/Controller -v    
    ./vendor/bin/simple-phpunit tests/AppBundle/Repository -v
   *in the above order* or run once as below
   
    ./vendor/bin/simple-phpunit tests/AppBundle -v
  ### Test results:
 ![Tests Example](https://i.imgur.com/O4IG7Jw.png)
    
