HR PROJECT PROGRESS
========================

Add a new bundle called User Bundle

$app/console generate:bundle --namespace=HRPROJECT\UserBundle --format=yml


Adding Security feature to the project to protect unauthorised access to site. User must login to access secured information in
the project

Link:
http://symfony.com/doc/2.3/cookbook/security/entity_provider.html
http://www.ens.ro/2012/08/09/symfony2-jobeet-day-13-security/

First of all create an Entity


Doctrine Migration
Follow the following link on how to add migration bundle into symfony project
http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html

1. Add th following to your composer.json
{
    "require": {
        "doctrine/migrations": "dev-master",
        "doctrine/doctrine-migrations-bundle": "dev-master"
    }
}

2. then install the vendor
$composer update

3. and finally register migration bundle into the project
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        //...
        new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
    );
}

First of all generate an empty migration file
$php app/console doctrine:migrations:generate

and then generate the actual migration
$php app/console doctrine:migrations:migrate

Useful links on migration https://test-sf-doc-es.readthedocs.org/en/latest/cookbook/doctrine/migrations.html


Now We have added how to login against users stored in database. We have not add anything that stops non active user to login
How to exclude nonActive users:
Extends User entity from AdvancedUserInterface instead of UserInterface. Add 4 abstract methods comes with AdvancedUserInterface
isAccountNonExpired(), isAccountNonLocked(), isCredentialsNonExpired() and isEnabled(). To add them just simply press alt+Enter

Now we have added registration system, so Admin can add new user account but we have not added ROLE load from the database

Add ROLE entity

Note: Make sure you have implement Seriazeable interface in both Role and User entity otherwise you may get error something like 'getRole on non-Onject ..'
http://blog.jmoz.co.uk/symfony2-fosuserbundle-role-entities/


1. Adding Fixture bundle into project:
----------------------------------

Add following line to composer.json

"require": {
    "doctrine/doctrine-fixtures-bundle": "2.2.*"
}

2. Then update composer
$composer update doctrine/doctrine-fixtures-bundle


3. Then register the bundle in appKernel

public function registerBundles()
{
    $bundles = array(
        // ...
        new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        // ...
    );
    // ...
}


Now create a fixture called LoadUserData that will add a user into user table

Load the fixture by following command

$app/console doctrine:fixtures:load

Now using user we will not be able to login to the system as password needed to be hashed and encrypted
Let's add hash and encoder to the LoadFixtureData fixture and execute fixture loading command $app/console doctrine:fixtures:load. Now we can see password is encoded and
we should be able to use user to login to the system

Links:http://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html

UNtil Now we have way to upload image in local and awazon s3 bucket. We can display images from
local storage but from Amazon S3 bucket. Next task will be to add code so we can upload and display
images between local and amazon by simply change config file





