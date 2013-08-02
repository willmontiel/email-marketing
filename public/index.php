<?php

try {

    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
		'../app/plugins/',
        '../app/models/',
        '../app/forms/',
        '../app/library/',
    ))->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();

	
	/*
	 * Dispatcher Object
	 */
	$di->set('dispatcher', function() use ($di) {

		$eventsManager = $di->getShared('eventsManager');

		$security = new Security($di);

		/**
		 * We listen for events in the dispatcher using the Security plugin
		 */
		$eventsManager->attach('dispatch', $security);

		$dispatcher = new Phalcon\Mvc\Dispatcher();
		$dispatcher->setEventsManager($eventsManager);

		return $dispatcher;
	});
  
	
	$di->set('router', function () {
		
		$router = new \Phalcon\Mvc\Router\Annotations();
		
		$router->addResource('Field', '/field');
		$router->addResource('Api', '/api');
		
		return $router;
	});
	
	
	/*
	 * Security Object, utilizado para validacion y creacion de contraseñas
	 */
	$di->set('security2', function(){

		$security2 = new Phalcon\Security();

		//Set the password hashing factor to 12 rounds
		$security2->setWorkFactor(12);

		return $security2;
    }, true);

	/*
	 * Profiler Object. Lo utilizamos en modo de depuracion/desarrollo para
	 * determinar los tiempos de ejecucion de SQL
	 */
	$di->set('profiler', function(){
		return new \Phalcon\Db\Profiler();
	}, true);	
	

	/*
	 * Database Object, conexion primaria a la base de datos
	 */
	$di->set('db', function() use ($di) {
		// Events Manager para la base de datos
		$eventsManager = new \Phalcon\Events\Manager();
		
		// Profiler
		$profiler = $di->get('profiler');
		
//		$eventsManager->attach('db', function ($event, $connection) use ($profiler) {
//			if ($event->getType() == 'beforeQuery') {
//				$profiler->startProfile($connection->getSQLStatement());
//			}
//			else if ($event->getType() == 'afterQuery') {
//				$profiler->stopProfile();
//			}
//		});
		
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => "localhost",
            "username" => "root",
            "password" => "",
            "dbname" => "emarketing_db"
        ));
		
		$connection->setEventsManager($eventsManager);
		
		return $connection;
		
    });

	/*
	 * Url Object, utilizado para crear URLs
	 */
    $di->set('url', function() {
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri('/emarketing/');
        return $url;
    });

	
	/*
	 * Log Object, utilizado para logging en general a archivo
	 */
	$di->set('logger', function () {
		// Archivo de log
		return new \Phalcon\Logger\Adapter\File("../app/logs/debug.log");
	});
    
	/*
	 * Volt Object, engine de templates
	 */
    $di->set('volt', function($view, $di) {
	    $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
		$compiler = $volt->getCompiler();
		
		$compiler->addFilter('numberf', function ($resolvedArgs, $exprArgs) {
			return 'number_format(' . $resolvedArgs . ', 0, \',\', \'.\')';
		});
		
        $volt->setOptions(array(
            "compileAlways" => true,
			"compiledPath" => "../app/compiled-templates/",
			"compiledExtension" => ".compiled"
        ));

		return $volt;
    });

	
    /*
	 * View Object
	 */
    $di->set('view', function() {
        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir('../app/views/');
        $view->registerEngines(array(
            ".volt" => 'volt'
        ));
        return $view;
    });
    
	/*
	 * Gestor de sesiones
	 */
    $di->setShared('session', function() {
        $session = new Phalcon\Session\Adapter\Files(
				array(
					'uniqueId' => 'emarketing'
		));
        $session->start();
        return $session;
    });
    
	/*
	 * Flash Object, para mantener mensajes flash entre una página y otra
	 */
    $di->set('flash', function(){
        $flash = new \Phalcon\Flash\Direct(array(
            'error' => 'alert alert-error',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
        ));
        return $flash;
    });

    //Handle the request
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();
	
	// Grabar en LOG
	$logger = $di->get('logger');
	$profiles = $di->get('profiler')->getProfiles();
	
	if (count($profiles) > 0) {
	
		$logger->log("==================== Application Profiling Information ========================", \Phalcon\Logger::INFO);
		foreach ($profiles as $profile) {
			$str = '******************************************************' . PHP_EOL .
				   \sprintf('SQL Statement: [%s]', $profile->getSQLStatement()) . PHP_EOL .
				   \sprintf('Start time: [%d]', $profile->getInitialTime()) . PHP_EOL .
				   \sprintf('End time: [%d]', $profile->getFinalTime()) . PHP_EOL .
				   \sprintf('Total elapsed time: [%f]', $profile->getTotalElapsedSeconds()) . PHP_EOL .
				   '******************************************************';

			$logger->log($str, \Phalcon\Logger::INFO);
		}
		$logger->log("==================== Application Profiling Information End ====================", \Phalcon\Logger::INFO);
	}

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}
