<?php


/*
 * Bootstrap Phalcon
 */

//Register an autoloader
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
	'../../models/',
	'../../library/',
	'../../logic/',
))->register();

//Create a DI
$di = new Phalcon\DI\FactoryDefault();

/* Configuracion */
$config = new \Phalcon\Config\Adapter\Ini("../../config/configuration.ini");

/*
 * Profiler Object. Lo utilizamos en modo de depuracion/desarrollo para
 * determinar los tiempos de ejecucion de SQL
 */
$di->set('profiler', function(){
	return new \Phalcon\Db\Profiler();
}, true);	


$di->set('modelsMetadata', function() {

	// Create a meta-data manager with APC
	$metaData = new \Phalcon\Mvc\Model\MetaData\Files(array(
//			"lifetime" => 86400,
		"metaDataDir"   => "../../cache/metadata/"
	));
	return $metaData;
});

/*
 * Database Object, conexion primaria a la base de datos
 */
$di->set('db', function() use ($di, $config) {
	// Events Manager para la base de datos
	$eventsManager = new \Phalcon\Events\Manager();

	if ($config->general->profiledb) {
		// Profiler
		$profiler = $di->get('profiler');
		$eventsManager->attach('db', function ($event, $connection) use ($profiler) {
			if ($event->getType() == 'beforeQuery') {
				$profiler->startProfile($connection->getSQLStatement());
			}
			else if ($event->getType() == 'afterQuery') {
				$profiler->stopProfile();
			}
		});
	}

	$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config->database->toArray());

	$connection->setEventsManager($eventsManager);

	return $connection;

});

$tmpdir = new stdClass;
$tmpdir->dir = dirname(__FILE__) . '/../../../tmp';
$di->set('tmppath', $tmpdir);

/*
 * Log Object, utilizado para logging en general a archivo
 */
$di->set('logger', function () {
	// Archivo de log
	return new \Phalcon\Logger\Adapter\File("../../logs/bgprocess.log");
});

$di->set('modelsManager', function(){
	return new Phalcon\Mvc\Model\Manager();
});
