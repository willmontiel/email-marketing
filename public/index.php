<?php
require_once '../app/autoload.php';

try {
	$app = new \EmailMarketing\General\AppObjects();
	$app->setConfigPath("../app/config/configuration.ini");
	
	// Create timer object
	$timer = new TimerObject();
	// Start counting
	$timer->startTimer('app', 'The whole app');
	
	$app->configure();
	
	$di = $app->getDi();
	
	$di->set('timerObject', $timer);
	//Handle the request
	$application = new \Phalcon\Mvc\Application($di);
	echo $application->handle()->getContent();
	
	// Finalizar timer
	$timer->endTimer('app');

	// Grabar en el log
	$di->get('logger')->log($timer);

	// Grabar en LOG toda la ejecucion de SQL del profiler
	// Solamente si esta configurado asi
	if ($app->getConfig()->general->profiledb) {
		$dblogger = new \Phalcon\Logger\Adapter\File("../app/logs/dbdebug.log");;
		$profiles = $di->get('profiler')->getProfiles();
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
} 
catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}
