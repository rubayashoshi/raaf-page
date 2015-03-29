How to install phpunit with composer
------------------------------------
To install PHPUnit with Composer on Symfony 2.1.6, Simply add following to require section in composer.json.

 "require": {
        .......
        “phpunit/phpunit”: “3.7.*”
        .....
 }

 and then run composer update command

 $composer update


Run your first test

$./vendor/phpunit/phpunit/phpunit.php -c app/ src/HRPROJECT/

