<?php
//Register an autoloader
$dir = dirname(__FILE__);

$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
	$dir . '/../controllers/',
	$dir . '/../plugins/',
	$dir . '/../models/',
	$dir . '/../forms/',
	$dir . '/../library/',
	$dir . '/../logic/',
	$dir . '/../editorlogic/',
));

// register autoloader
$loader->register();

initialize($dir);

/*
 * Database Object, conexion primaria a la base de datos
 */
function initialize($dir)
{
	//Create a DI
	$di = new Phalcon\DI\FactoryDefault();

	/* Configuracion */
	$config = new \Phalcon\Config\Adapter\Ini("{$dir}/../config/configuration.tests.ini");
	$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config->database->toArray());

	$di->set('db', $connection);
	
}

	