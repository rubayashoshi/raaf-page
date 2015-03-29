Install Xdebug in Ubuntu
=========================
$sudo apt-get install php5-xdebug


The package should modify your INI file for you, but just in case you need to edit it yourself
open it up and make the following modification - on Ubuntu its typically at
/etc/php5/apache2/php.ini - add the following line.

zend_extension="/usr/lib/php5/20121212+lfs/xdebug.so"

then also add following line close Zend_extension in php.ini file
xdebug.remote_enable=1
xdebug.remote_host=localhost
xdebug.remote_port=9000
zend_extension="/usr/lib/php5/20121212+lfs/xdebug.so"

and then restart apache web server
$sudo service apache2 reload