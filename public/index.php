<?php

try {

    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        '../app/Forms/',
        '../app/class/',
        '../app/controllers/',
        '../app/models/',
        '../app/plugins/'
    ))->register();

    
    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();
    
//    $di->set('dispatcher', function() use ($di) {
//
//		$eventsManager = $di->getShared('eventsManager');
//
//		$security = new Security($di);
//
//		/**
//		 * We listen for events in the dispatcher using the Security plugin
//		 */
//		$eventsManager->attach('dispatch', $security);
//
//		$dispatcher = new Phalcon\Mvc\Dispatcher();
//		$dispatcher->setEventsManager($eventsManager);
//
//		return $dispatcher;
//	});

    //Set the database service
    $di->set('security2', function(){

        $security2 = new Phalcon\Security();

        //Set the password hashing factor to 12 rounds
        $security2->setWorkFactor(12);

        return $security2;
    }, true);
    

    $di->set('db', function(){
        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => "localhost",
            "username" => "root",
            "password" => "",
            "dbname" => "emarketing_db"
        ));
    });



    $di->set('url', function() {
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri('/emarketing/');
            return $url;
    });


        //Registering Volt as template engine
    $di->set('view', function() {

    $view = new \Phalcon\Mvc\View();

    $view->setViewsDir('../app/views/');

    $view->registerEngines(array(
        ".volt" => 'Phalcon\Mvc\View\Engine\Volt'
    ));

    return $view;
});

//Start the session the first time when some component request the session service
$di->setShared('session', function() {
    $session = new Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});


 //Register 
 //the flash service with custom CSS classes
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

} catch(Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}

