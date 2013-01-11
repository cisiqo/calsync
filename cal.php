<?php

$baseURI = str_replace("\\","/",str_replace(__DIR__,"",__FILE__));

# Backends
$authBackend = new \Sabre\DAV\Auth\Backend\PDO($GLOBALS["DB"]->getPDO());
$principalBackend = new \Sabre\DAVACL\PrincipalBackend\PDO($GLOBALS["DB"]->getPDO());
$calendarBackend = new \Sabre\CalDAV\Backend\PDO($GLOBALS["DB"]->getPDO());

# Directory structure
$nodes = array(
    new \Sabre\CalDAV\Principal\Collection($principalBackend),
    new \Sabre\CalDAV\CalendarRootNode($principalBackend, $calendarBackend),
);

# Initializing server
$server = new \Sabre\DAV\Server($nodes);
$server->setBaseUri($baseURI);

# Server Plugins
$server->addPlugin(new \Sabre\DAV\Auth\Plugin($authBackend,"calsync"));
$server->addPlugin(new \Sabre\DAVACL\Plugin());
$server->addPlugin(new \Sabre\CalDAV\Plugin());

# And off we go!
$server->exec();

?>