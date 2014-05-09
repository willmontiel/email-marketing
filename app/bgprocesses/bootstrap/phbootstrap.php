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
	'../../library/facebook/',
	'../../library/twitter/',
	'../../logic/',
	'../../editorlogic/',
))->register();

$loader->registerNamespaces(
	array(
		'EmailMarketing\\General' => '../../general/'
	),
	true
);

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
 * Configuración de las URL's
 */
$urlManagerObj = new UrlManagerObject($config);
$di->set('urlManager', $urlManagerObj); 

/*
 * Profiler Object. Lo utilizamos en modo de depuracion/desarrollo para
 * determinar los tiempos de ejecucion de SQL
 */
$di->set('profiler', function(){
	$pr = new \Phalcon\Db\Profiler();
	return $pr;
}, true);	

// Create timer object
$timer = new TimerObject();
// Start counting
$timer->startTimer('app', 'The whole app');

$di->set('timerObject', $timer);


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
$di->setShared('db', function() use ($di, $config) {
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

	// Configuracion de la base de datos... adicionar modo persistente para
	// evitar desconexion
	$c = $config->database->toArray();
	$c['persistent'] = true;
	$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($c);

	$connection->setEventsManager($eventsManager);

	return $connection;

});

$tmpdir = new stdClass;
$tmpdir->dir = dirname(__FILE__) . '/../../../tmp';
$di->set('tmppath', $tmpdir);

/*
 * Directorio de assets privados
 */

$asset = new \stdClass;
$asset->dir = '../../' .$config->general->assetsfolder;
$asset->url = '/' . $di->get('urlManager')->getAppUrlAsset() . '/';
$di->set('asset', $asset);

/*
 * Configuración MTA
 */
$mtaConfig = new stdClass();
$mtaConfig->address = $config->mta->address;
$mtaConfig->port = $config->mta->port;
$mtaConfig->mailClass = $config->mta->mailclass;
$di->set('mtadata', $mtaConfig);

/*
 * Configuración Facebook App 
 */
$fbapp = new stdClass();
$fbapp->iduser = $config->fbapp->id;
$fbapp->token = $config->fbapp->token;
$di->set('fbapp', $fbapp);

/*
* Configuración Twitter App 
*/
$twapp = new stdClass();
$twapp->iduser = $config->twapp->id;
$twapp->token = $config->twapp->token;
$di->set('twapp', $twapp);

/*
* Configuración Sockets
*/
$sockets = new stdClass();
$sockets->importrequest = $config->sockets->importrequest;
$sockets->importtochildren = $config->sockets->importtochildren;
$sockets->importfromchild = $config->sockets->importfromchild;
$sockets->mailrequest = $config->sockets->mailrequest;
$sockets->mailtochildren = $config->sockets->mailtochildren;
$sockets->mailfromchild = $config->sockets->mailfromchild;
$di->set('sockets', $sockets);

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

/*
 * Cache, se encarga de comunicarse con Memcache
 */
$di->set('cache', function (){

	$frontCache = new Phalcon\Cache\Frontend\Data(array(
		"lifetime" => 172800
	));

	$cache = new Phalcon\Cache\Backend\Memcache($frontCache, array(
		"host" => "localhost",
		"port" => "11211"
	));
	return $cache;
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
	Phalcon\DI::getDefault()->get('profiler')->reset();
}

function get_dbase_profile()
{
	$profiles = Phalcon\DI::getDefault()->get('profiler')->getProfiles();

	if (count($profiles) > 0) {

		$str = "==================== Application Profiling Information ========================\n";
		foreach ($profiles as $profile) {
			$str .= '******************************************************' . PHP_EOL .
				   \sprintf('SQL Statement: [%s]', $profile->getSQLStatement()) . PHP_EOL .
				   \sprintf('Start time: [%d]', $profile->getInitialTime()) . PHP_EOL .
				   \sprintf('End time: [%d]', $profile->getFinalTime()) . PHP_EOL .
				   \sprintf('Total elapsed time: [%f]', $profile->getTotalElapsedSeconds()) . PHP_EOL .
				   '******************************************************' . PHP_EOL;

		}
		$str .= "==================== Application Profiling Information End ====================\n";
	}
	Phalcon\DI::getDefault()->get('profiler')->reset();
	
	return $str;
}