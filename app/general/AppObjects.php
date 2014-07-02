<?php

namespace EmailMarketing\General;

class AppObjects
{
	protected $config;
	/**
	 *
	 * @var \Phalcon\DI
	 */
	protected $di;
	protected $status;
	protected $urlManager;
	protected $allowed_ips;
	protected $ip;

	/**
	 * Archivo de configuración del sistema necesario para iniciar la plataforma 
	 * @param String ruta del archivo de configuración
	 */
	public function setConfigPath($configPath)
	{
		$this->config = new \Phalcon\Config\Adapter\Ini($configPath);
		$this->status = $this->config->system->status;
		$this->allowed_ips = array();
		foreach ($this->config->system->override_ip as $ip) {
			$this->allowed_ips[] = $ip;
		}
		$this->ip = $_SERVER['SERVER_ADDR'];
	}
	
	public function configure()
	{
		$this->createDi();

		$this->setChatConfig();
		
		if (!$this->config->system->status && !in_array($this->ip, $this->allowed_ips)) {
			$this->setAppPath();
			$this->setDispatcher();
			$this->setLogger();
			$this->setUrlManagerObject();
			$this->setUri();
			$this->setViewSystemNotAvailable();
		}
		else {
			$this->setAppPath();
			$this->setUrlManagerObject();
			$this->setUri();
			$this->setRouter();
			
			$this->setAcl();
			$this->setMemcache();
			$this->setDispatcher();
			$this->setSecurityHash();
			$this->setSessionManager();
			
			$this->setFlashSessionMessages();
			$this->setAdministrativeMessages();
			
			$this->setModelsMetadata();
			$this->setMtaConfig();
			
			$this->setDb();
			$this->setModelsManager();
			
			$this->setPrivateAssetsFolder();
			$this->setPublicAssetsFolder();
			$this->setTmpFolder();
			$this->setPublicFootersFolder();
			
			$this->setFacebookAppConfig();
			$this->setTwitterAppConfig();
			$this->setSockectsConfig();
			$this->setGoogleAnalitycsConfig();
			$this->setMailReportsFolder();
			
			$this->setLogger();
			$this->setProfiler();
			
			$this->setElements();
			$this->setView();
			$this->setVoltCompiler();
		}
	}
	
	
	protected function setChatConfig()
	{
		$chat = new \stdClass();
		if (isset($this->config->olark) && isset($this->config->olark->enabled)) {
			$chat->enabled = $this->config->olark->enabled;
		}
		else {
			$chat->enabled = false;
		}
		
		$this->di->set('chat', $chat);
	}


	/**
	 * Creación del inyector de dependencias
	 */
	private function createDi()
	{
		$this->di = new \Phalcon\DI\FactoryDefault();
	}
	
	/**
	 * Ruta principal de la aplicacion
	 * @return DI object
	 */
	private function setAppPath()
	{
		// Ruta de APP
		$apppath = realpath('../');
		$this->di->set('appPath', function () use ($apppath) {
			$obj = new \stdClass;
			$obj->path = $apppath;

			return $obj;
		});
	}
	
	/**
	 * El objeto encargado de armar las url puntuales basandose en el archivo de configuración
	 */
	private function setUrlManagerObject()
	{
		$this->urlManager = new \UrlManagerObject($this->config);
		$this->di->set('urlManager', $this->urlManager);    
	}
	
	/**
	 * Configuración de la base URI, para generar automaticacmente todas las direcciones posibles dentro de la carpeta 
	 * principal de la aplicación
	 * @return DI object
	 */
	private function setUri()
	{
		$urlManagerObj = $this->urlManager;
		$this->di->set('url', function() use ($urlManagerObj) {
			$url = new \Phalcon\Mvc\Url();
			$uri = $urlManagerObj->getBaseUri();

			// Adicionar / al inicio y al final
			if (substr($uri, 0, 1) != '/') {
				$uri = '/' . $uri;
			}
			if (substr($uri, -1) != '/') {
				$uri .= '/';
			}

			$url->setBaseUri($uri);
			return $url;
		});
	}
	
	/**
	 * Encargado de enrutar las peticiones de acuerdo a su url
	 * @return DI object
	 */
	public function setRouter()
	{
		$this->di->set('router', function () {
			$router = new \Phalcon\Mvc\Router\Annotations();

			$router->addResource('Field', '/field');
			$router->addResource('Api', '/api');
			$router->addResource('Dbaseapi', '/api/dbase');
			$router->addResource('Contactlistapi', '/api/contactlist');
			$router->addResource('Segmentapi', '/api/segment');
			$router->addResource('Apistatistics', '/apistatistics');
			$router->addResource('Apiversionone', '/api/v1');

			return $router;
		});
	}
	
	/**
	 * Encargado de escuchar cada peticion(controlador/acción) que hace el usuario a la plataforma
	 * @return DI object
	 */
	private function setDispatcher()
	{
		$di = $this->di;
		$status = $this->status;
		$allowed_ips = $this->allowed_ips;
		$ip = $this->ip;
		
		$di->set('dispatcher', function() use ($di, $status, $allowed_ips, $ip) {

			$eventsManager = $di->getShared('eventsManager');

			$security = new \Security($di, $status, $allowed_ips, $ip);
			/**
			 * We listen for events in the dispatcher using the Security plugin
			 */
			$eventsManager->attach('dispatch', $security);

			$dispatcher = new \Phalcon\Mvc\Dispatcher();
			$dispatcher->setEventsManager($eventsManager);

			return $dispatcher;
		});
		
	}
	
	/**
	 * Comunicación con Memcache
	 * @return DI object
	 */
	private function setMemcache()
	{
		$conf = $this->config;
		$this->di->set('cache', function () use ($conf){

			$frontCache = new \Phalcon\Cache\Frontend\Data(array(
				"lifetime" => 172800
			));

			if (class_exists('Memcache')) {
				$cache = new \Phalcon\Cache\Backend\Memcache($frontCache, array(
					"host" => "localhost",
					"port" => "11211"
				));
			}
			else {
				$cache = new \Phalcon\Cache\Backend\File($frontCache, array(
				    "cacheDir" => $conf->general->tmpdir
				));
			}
			return $cache;
		});
	}
	
	/**
	 * Se encargar de injectar la clase que administra el menu principal de la aplicación
	 * @return DI object
	 */
	private function setElements()
	{
		$this->di->set('elements', function(){
			return new \VisualElements();
		});
	}
	
	/**
	 * Lista de control de usuario para permisos sobre recursos
	 * @return DI object
	 */
	private function setAcl()
	{
		$this->di->set('acl', function(){
			$acl = new \Phalcon\Acl\Adapter\Memory();
			$acl->setDefaultAction(\Phalcon\Acl::DENY);

			return $acl;
		});
	}
	
	/**
	 * Hash para validacion y creacion de contraseñas de los usuarios
	 * @return DI object
	 */
	private function setSecurityHash()
	{
		$this->di->set('security2', function(){

			$security2 = new \Phalcon\Security();

			//Set the password hashing factor to 12 rounds
			$security2->setWorkFactor(12);

			return $security2;
		}, true);
	}
	
	/**
	 * Models metadata crea metadatos en cache de los modelos en la aplicación
	 * para evitar estar consultandolos
	 * @return DI object
	 */
	private function setModelsMetadata()
	{
		$this->di->set('modelsMetadata', function() {
			$metaData = new \Phalcon\Mvc\Model\MetaData\Files(array(
	//			"lifetime" => 86400,
				"metaDataDir"   => "../app/cache/metadata/"
			));
			return $metaData;
		});
	}
	
	/**
	 * Database Object, conexion primaria a la base de datos
	 * @return DI object
	 */
	private function setDb()
	{
		$config = $this->config;
		$di = $this->di;
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

			$connection = new \Phalcon\Db\Adapter\Pdo\Mysql($config->database->toArray());

			$connection->setEventsManager($eventsManager);

			return $connection;

		});
	}
	
	/**
	 * Para creación de consultas PHQL
	 * @return DI object
	 */
	private function setModelsManager()
	{
		$this->di->set('modelsManager', function(){
			return new \Phalcon\Mvc\Model\Manager();
		});
	}


	/**
	 * Directorio de assets privados
	 */
	private function setPrivateAssetsFolder()
	{
		$asset = new \stdClass;
		$asset->dir = $this->config->general->assetsfolder;
		$asset->url = '/' . $this->urlManager->getAppUrlAsset() . '/';
		$this->di->set('asset', $asset);
	}
	
	/**
	 * Directorio de assets publicos
	 */
	private function setPublicAssetsFolder()
	{
		$templatesfolder = new \stdClass();
		$templatesfolder->dir = $this->config->general->templatesfolder;
		$this->di->set('templatesfolder', $templatesfolder);
	}
	
	/**
	 * Directorio de para archivos temporales
	 */
	private function setTmpFolder()
	{
		$tmpdir = new \stdClass;
		$tmpdir->dir = $this->config->general->tmpdir;
		$this->di->set('tmppath', $tmpdir);
	}
	
	/**
	 * Directorio de footers publicos
	 */
	private function setPublicFootersFolder()
	{
		$footersfolder = new \stdClass();
		$footersfolder->dir = $this->config->general->footersfolder;
		$this->di->set('footersfolder', $footersfolder);
	}
	
	/**
	 * Configuración MTA
	 */
	private function setMtaConfig()
	{
		$mtaConfig = new \stdClass();
		$mtaConfig->address = $this->config->mta->address;
		$mtaConfig->port = $this->config->mta->port;
		$mtaConfig->mailClass = $this->config->mta->mailclass;
		$this->di->set('mtadata', $mtaConfig);
	}
	
	/*
	 * Configuración Facebook App 
	 */
	private function setFacebookAppConfig()
	{
		$fbapp = new \stdClass();
		$fbapp->iduser = $this->config->fbapp->id;
		$fbapp->token = $this->config->fbapp->token;
		$this->di->set('fbapp', $fbapp);
	}
	
	/*
	 * Configuración Twitter App 
	 */
	private function setTwitterAppConfig()
	{
		$twapp = new \stdClass();
		$twapp->iduser = $this->config->twapp->id;
		$twapp->token = $this->config->twapp->token;
		$this->di->set('twapp', $twapp);
	}
	
	/**
	 * Configuración Sockets
	 */
	private function setSockectsConfig()
	{
		$sockets = new \stdClass();
		$sockets->importrequest = $this->config->sockets->importrequest;
		$sockets->importtochildren = $this->config->sockets->importtochildren;
		$sockets->importfromchild = $this->config->sockets->importfromchild;
		$sockets->mailrequest = $this->config->sockets->mailrequest;
		$sockets->mailtochildren = $this->config->sockets->mailtochildren;
		$sockets->mailfromchild = $this->config->sockets->mailfromchild;
		$this->di->set('sockets', $sockets);
	}
	
	/**
	 * Configuración Google Analytics 
	 */
	private function setGoogleAnalitycsConfig()
	{
		$googleAnalytics = new \stdClass();
		$googleAnalytics->utm_source = $this->config->googleanalytics->utm_source;
		$googleAnalytics->utm_medium = $this->config->googleanalytics->utm_medium;
		$this->di->set('googleAnalytics', $googleAnalytics);
	}
	
	/**
	 * Directorio de reportes de correo
	 */
	private function setMailReportsFolder()
	{
		$mailReportsDir = new \stdClass();
		$mailReportsDir->reports = $this->config->mailreports->tmpdirmailreports;
		$this->di->set('mailReportsDir', $mailReportsDir);
	}
	
	/**
	 * Gestor de sesiones
	 * @return DI object
	 */
	private function setSessionManager()
	{
		$this->di->setShared('session', function() {
			$session = new \Phalcon\Session\Adapter\Files(
					array(
						'uniqueId' => 'emarketing'
			));
			$session->start();
			return $session;
		});
	}
	
	/**
	 * Flash Object, para mantener mensajes flash entre una página y otra
	 * @return DI object
	 */
	private function setFlashSessionMessages()
	{
		$this->di->set('flashSession', function(){
			$flash = new \Phalcon\Flash\Session(array(
				'error' => 'bs-callout bs-callout-danger',
				'success' => 'bs-callout bs-callout-success',
				'notice' => 'bs-callout bs-callout-info',
				'warning' => 'bs-callout bs-callout-warning'
			));
			return $flash;
		});
	}
	
	/**
	 * FlashMessage Object, para mostrar mensajes informativos y administrativos a los usuarios
	 * @return DI object
	 */
	public function setAdministrativeMessages()
	{
		$this->di->set('flashMessage', function(){
			$flashMessage = new \FlashMessages();
			return $flashMessage;
		});
	}
	
	/**
	 * Log Object, utilizado para logging en general a archivo
	 * @return DI object
	 */
	private function setLogger()
	{
		$this->di->set('logger', function () {
			// Archivo de log
			return new \Phalcon\Logger\Adapter\File("../app/logs/debug.log");
		});
	}

	/**
	 * Profiler Object. Lo utilizamos en modo de depuracion/desarrollo para
	 * determinar los tiempos de ejecucion de SQL
	 * @return DI object
	 */
	private function setProfiler()
	{
		$this->di->set('profiler', function(){
			return new \Phalcon\Db\Profiler();
		}, true);
	}
	
	/**
	 * Compilador de archivos volt
	 * @param type $di
	 * @return DI object
	 */
	private function setVoltCompiler()
	{
		$di = $this->di;
		$di->setShared('volt', function($view, $di) {
			$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
			$compiler = $volt->getCompiler();

			$compiler->addFilter('int', function($resolvedArgs, $exprArgs) {
				return 'intval(' . $resolvedArgs . ')';
			});

			$compiler->addFilter('numberf', function ($resolvedArgs, $exprArgs) {
				return 'number_format(' . $resolvedArgs . ', 0, \',\', \'.\')';
			});

			$compiler->addFilter('change_spaces_in_between', function ($resolvedArgs, $exprArgs){
					return 'str_replace(" ", "_", ' . $resolvedArgs . ')';
				});

			$compiler->addFunction('value_in_array', function ($resolvedArgs, $exprArgs) use ($compiler){
							return 'in_array(' . $resolvedArgs . ')';
						});

			$compiler->addFunction('ember_customfield', function ($resolvedArgs, $exprArgs) {
							return 'CreateViewEmber::createField(' . $resolvedArgs . ')';
						});

			$compiler->addFunction('ember_customfield_xeditable', function ($resolvedArgs, $exprArgs) {
							return 'CreateViewEmber::createCustomFieldXeditable(' . $resolvedArgs . ')';
						});

			$compiler->addFunction('ember_customfield_options', function ($resolvedArgs, $exprArgs) {
							return 'CreateViewEmber::createOptions(' . $resolvedArgs . ')';
						});

			$compiler->addFunction('ember_customfield_options_xeditable', function ($resolvedArgs, $exprArgs) {
							return 'CreateViewEmber::createOptionsForXeditable(' . $resolvedArgs . ')';
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
			$compiler->addFunction('mail_options', function ($resolvedArgs, $exprArgs){
							return 'OptionsMail::getOptions(' . $resolvedArgs . ')';
						});
			$compiler->addFunction('programming_options', function ($resolvedArgs, $exprArgs){
							return 'ProgrammingOptions::getOptions(' . $resolvedArgs . ')';
						});
			$compiler->addFunction('smart_wizard', function ($resolvedArgs, $exprArgs){
							return 'SmartWizard::getWizard(' . $resolvedArgs . ')';
						});
			$compiler->addFunction('select_target', function ($resolvedArgs, $exprArgs){
							return 'SmartSelect::getSelectTarget(' . $resolvedArgs . ')';
						});

			$volt->setOptions(array(
				"compileAlways" => true,
				"compiledPath" => "../app/compiled-templates/",
				'stat' => true
			));

			return $volt;
		});
	}
	
	/**
	 * Encargado de configurar volt 
	 * @return DI object
	 */
	private function setView()
	{
		$this->di->set('view', function() {
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir('../app/views/');
			$view->registerEngines(array(
				".volt" => 'volt'
			));
			return $view;
		});
	}
	
	/**
	 * Encargado de configurar volt para la plataforma no esta disponible
	 * @return DI object
	 */
	private function setViewSystemNotAvailable()
	{
		$this->di->set('view', function() {
			$view = new \Phalcon\Mvc\View();
			$view->setViewsDir('../app/views/');
			$view->registerEngines(array(
				".volt" => 'Phalcon\Mvc\View\Engine\Volt'
			));
			return $view;
		});
	}

	public function getDi()
	{
		return $this->di;
	}
	
	public function getConfig()
	{
		return $this->config;
	}
}