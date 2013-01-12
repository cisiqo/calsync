<?php
define('SABRE_MYSQLDSN','mysql:host=127.0.0.1;dbname=baikal');
define('SABRE_MYSQLUSER','root');
define('SABRE_MYSQLPASS','111111');

$baseURI = str_replace("\\","/",str_replace(__DIR__,"",__FILE__));

$pdo = new \PDO(SABRE_MYSQLDSN,SABRE_MYSQLUSER,SABRE_MYSQLPASS);

require_once "SabreDAV/vendor/autoload.php";
# Backends
$authBackend = new \Sabre\DAV\Auth\Backend\PDO($pdo);
$principalBackend = new \Sabre\DAVACL\PrincipalBackend\PDO($pdo);
$calendarBackend = new \Sabre\CalDAV\Backend\PDO($pdo);

# Directory structure
$nodes = array(
    new \Sabre\CalDAV\Principal\Collection($principalBackend),
    new \Sabre\CalDAV\CalendarRootNode($principalBackend, $calendarBackend),
);

# Initializing server
$server = new \Sabre\DAV\Server($nodes);
$server->setBaseUri("/cal.php/");

# Server Plugins
$server->addPlugin(new \Sabre\DAV\Auth\Plugin($authBackend,"BaikalDAV"));
$server->addPlugin(new \Sabre\DAVACL\Plugin());
$server->addPlugin(new \Sabre\CalDAV\Plugin());

# And off we go!
$server->exec();

?>
