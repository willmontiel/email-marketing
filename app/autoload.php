<?php

	$loader = new \Phalcon\Loader();
	
	$loader->registerDirs(array(
		'../app/controllers/',
		'../app/plugins/',
		'../app/models/',
		'../app/forms/',
		'../app/library/',
		'../app/library/facebook/',
		'../app/library/twitter/',
		'../app/logic/',
		'../app/editorlogic/',
		'../app/bgprocesses/sender/',
	));

	$loader->registerNamespaces(array(
				'EmailMarketing\\SocialTracking' => '../app/SocialTracking/',
				'EmailMarketing\\General' => '../app/general/'
			), true
	);

	$loader->registerClasses(array(
		"simple_html_dom" => "../app/library/simple_html_dom.php",
		"geoip_open" => "../app/library/geoip.inc",
	));
	
	// register autoloader
	$loader->register();