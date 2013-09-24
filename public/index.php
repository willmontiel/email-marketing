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
        '../app/logic/',
    ))->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();
	
	// Create timer object
	$timer = new TimerObject();
	// Start counting
	$timer->startTimer('app', 'The whole app');
	
	$di->set('timerObject', $timer);
	
	

	/* Configuracion */
	$config = new \Phalcon\Config\Adapter\Ini("../app/config/configuration.ini");
	
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

	$di->set('acl', function(){
		$acl = new Phalcon\Acl\Adapter\Memory();
		$acl->setDefaultAction(Phalcon\Acl::DENY);
		
		return $acl;
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
	

	$di->set('modelsMetadata', function() {

		// Create a meta-data manager with APC
		$metaData = new \Phalcon\Mvc\Model\MetaData\Files(array(
//			"lifetime" => 86400,
			"metaDataDir"   => "../app/cache/metadata/"
		));

		return $metaData;
	});
	
	/*
	 * Database Object, conexion primaria a la base de datos
	 */
	$di->set('db', function() use ($di, $config) {
		// Events Manager para la base de datos
		$eventsManager = new \Phalcon\Events\Manager();
		
//		if ($config->general->profiledb) {
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
//		}
		
        $connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config->database->toArray());
		
		$connection->setEventsManager($eventsManager);
		
		return $connection;
		
    });

	/*
	 * Url Object, utilizado para crear URLs
	 */
    $di->set('url', function() use ($config) {
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri($config->general->baseuri);
        return $url;
    });
	
	$apiurl = new stdClass;
	$apiurl->url = $config->general->apiurlprefix;
	$di->set('apiurlbase', $apiurl);
	$tmpdir = new stdClass;
	$tmpdir->dir = dirname(__FILE__) . '/../tmp';
	$di->set('tmppath', $tmpdir);

	
	/*
	 * Log Object, utilizado para logging en general a archivo
	 */
	$di->set('logger', function () {
		// Archivo de log
		return new \Phalcon\Logger\Adapter\File("../app/logs/debug.log");
	});
	
	$di->set('modelsManager', function(){
		return new Phalcon\Mvc\Model\Manager();
	});
    
	/*
	 * Volt Object, engine de templates
	 */
    $di->set('volt', function($view, $di) {
	    $volt = new Phalcon\Mvc\View\Engine\Volt($view, $di);
		$compiler = $volt->getCompiler();
		
		$compiler->addFilter('int', function($resolvedArgs, $exprArgs) {
			return 'intval(' . $resolvedArgs . ')';
		});
		
		$compiler->addFilter('numberf', function ($resolvedArgs, $exprArgs) {
			return 'number_format(' . $resolvedArgs . ', 0, \',\', \'.\')';
		});
		
		$compiler->addFunction('ember_customfield', function ($resolvedArgs, $exprArgs) {
                        return 'CreateViewEmber::createField(' . $resolvedArgs . ')';
                    });

		$compiler->addFunction('ember_customfield_options', function ($resolvedArgs, $exprArgs) {
                        return 'CreateViewEmber::createOptions(' . $resolvedArgs . ')';
                    });
		$compiler->addFunction('ember_textfield', function ($resolvedArgs, $exprArgs) {
						return 'CreateViewEmber::createEmberTextField(' . $resolvedArgs . ')';
					});
		$compiler->addFunction('get_inactive', function ($resolvedArgs, $exprArgs) {
						return 'ContactCounter::getInactive(' . $resolvedArgs . ')';
					});
		$compiler->addFunction('acl_Ember', function ($resolvedArgs, $exprArgs){
						return 'CreateAclEmber::getAclToEmber(' . $resolvedArgs . ')';
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
			'warning' => 'alert alert-block'
        ));
        return $flash;
    });

    //Handle the request
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

	// Finalizar timer
	$timer->endTimer('app');
	
	// Grabar en el log
	$di->get('logger')->log($timer);
	
	// Grabar en LOG toda la ejecucion de SQL del profiler
	// Solamente si esta configurado asi
	if ($config->general->profiledb) {
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

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}
