RAAF PAGE PROJECT JOURNEY
========================

Add JMS DI EXTRA BUNDLE INTO PROJECT
// composer.json
{
    // ...
    require: {
        // ...
        "jms/security-extra-bundle": "dev-master"
    }
}

And execute following command
$ php composer.phar update jms/security-extra-bundle