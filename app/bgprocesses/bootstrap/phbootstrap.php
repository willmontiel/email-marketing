<?php


/*
 * Bootstrap Phalcon
 */

//Register an autoloader
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
	'../sender',
	'../../models/',
	'../../library/',
	'../../logic/',
))->register();

// Ruta de APP
$apppath = realpath('../../../');

//Create a DI
$di = new Phalcon\DI\FactoryDefault();

/* Ruta de APP */
$di->set('appPath', function () use ($apppath) {
	$obj = new stdClass;
	$obj->path = $apppath;
	
	return $obj;
});

/* Configuracion */
$config = new \Phalcon\Config\Adapter\Ini("../../config/configuration.ini");

/*
 * Profiler Object. Lo utilizamos en modo de depuracion/desarrollo para
 * determinar los tiempos de ejecucion de SQL
 */
$di->set('profiler', function(){
	return new \Phalcon\Db\Profiler();
}, true);	

// Create timer object
$di->set('timerObject',  new TimerObject());

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
		$timer = $di->get('timerObject');
		$eventsManager->attach('db', function ($event, $connection) use ($profiler, $timer) {
			if ($event->getType() == 'beforeQuery') {
				$profiler->startProfile($connection->getSQLStatement());
				$timer->startTimer('SQL', 'Query Execution');
			}
			else if ($event->getType() == 'afterQuery') {
				$profiler->stopProfile();
				$timer->endTimer('SQL');
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

$di->set('publisher', function () {
	$context = new ZMQContext(1);
	$publisher = new ZMQSocket($context, ZMQ::SOCKET_PUB);
	$publisher->bind("tcp://*:5558");
	return 	$publisher;
});

function print_dbase_profile()
{
	$dblogger = new \Phalcon\Logger\Adapter\File("../../logs/bgdbdebug.log");
	$profiles = Phalcon\DI::getDefault()->get('profiler')->getProfiles();

	if (count($profiles) > 0) {

		$dblogger->log("==================== Application Profiling Information ========================", \Phalcon\Logger::INFO);
		foreach ($profiles as $profile) {
			$str = '******************************************************' . PHP_EOL .
				   \sprintf('SQL Statement: [%s]', $profile->getSQLStatement()) . PHP_EOL .
				   \sprintf('Start time: [%d]', $profile->getInitialTime()) . PHP_EOL .
				   \sprintf('End time: [%d]', $profile->getFinalTime()) . PHP_EOL .
				   \sprintf('Total elapsed time: [%f]', $profile->getTotalElapsedSeconds()) . PHP_EOL .
				   '******************************************************';

			$dblogger->log($str, \Phalcon\Logger::INFO);
		}
		$dblogger->log("==================== Application Profiling Information End ====================", \Phalcon\Logger::INFO);
	}
}