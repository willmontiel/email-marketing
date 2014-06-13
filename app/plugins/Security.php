<?php

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Acl;
/**
 * Security
 *
 * Este es la clase que proporciona los permisos a los usuarios. Esta clase decide si un usuario pueder hacer determinada
 * tarea basandose en el tipo de ROLE que posea
 */
class Security extends Plugin
{
	protected $serverStatus;
	protected $allowed_ips;
	protected $ip;

	public function __construct($dependencyInjector, $serverStatus = 0, $allowed_ips = null, $ip = null)
	{
		$this->_dependencyInjector = $dependencyInjector;
		$this->serverStatus = $serverStatus;
		$this->allowed_ips = $allowed_ips;
		$this->ip = $ip;
	}

	public function getAcl()
	{
		/*
		 * Buscar ACL en cache
		 */
		$acl = $this->cache->get('acl-cache');
		
		if (!$acl) {
			// No existe, crear objeto ACL
	
//			$acl = new Phalcon\Acl\Adapter\Memory();
//			$acl->setDefaultAction(Phalcon\Acl::DENY);
			$acl = $this->acl;

			$userroles = Role::find();

			$modelManager = Phalcon\DI::getDefault()->get('modelsManager');

			$sql = "SELECT Resource.name AS resource, Action.action AS action 
					FROM Action
						JOIN Resource ON ( Action.idResource = Resource.idResource )";

			$results = $modelManager->executeQuery($sql);

			$userandroles = $modelManager->executeQuery('SELECT Role.name AS rolename, Resource.name AS resname, Action.action AS actname
													 FROM Allowed
														JOIN Role ON ( Role.idRole = Allowed.idRole ) 
														JOIN Action ON ( Action.idAction = Allowed.idAction ) 
														JOIN Resource ON ( Action.idResource = Resource.idResource ) ');
			
			//Registrando roles
			foreach ($userroles as $role){
				$acl->addRole(new Phalcon\Acl\Role($role->name));
			}
			
			//Registrando recursos
			$resources = array();
			foreach ($results as $key) {
				
				if(!isset($resources[$key['resource']])){
					$resources[$key['resource']] = array($key['action']);
				}
				$resources[$key['resource']][] = $key['action'];
			}
			
			foreach ($resources as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}

			//Relacionando roles y recursos desde la base de datos
			foreach($userandroles as $role) {
				$acl->allow($role->rolename, $role->resname, $role->actname);
			}
			
			$this->cache->save('acl-cache', $acl);
		}

		// Retornar ACL
		return $acl;
		
	}
	
	protected function getControllerMap()
	{
		$map = $this->cache->get('controllermap-cache');
		if (!$map) {
			$map = array(
		//* RELEASE 0.1.0 *//
				//Tests
				'test::transactionsegment' => array(),				
				
				'error::index' => array(),
				'error::notavailable' => array(),
				'error::unauthorized' => array(),
				'error::link' => array(),
				'session::signin' => array(),
				'session::login' => array(),
				'session::logout' => array(),
				'session::recoverpass' => array(),
				'session::setnewpass' => array(),
				'session::reset' => array(),
				'session::logoutfromthisaccount' => array(),
				'session::loginlikethisuser' => array('account' => array('login how any user')),
				'track::open' => array(),
				'track::click' => array(),
				'track::mtaevent' => array(),
				'track::opensocial' => array(),
				'track::clicksocial' => array(),
				'webversion::show' => array(),
				'webversion::share' => array(),
				'socialmedia::share' => array(),
				'unsubscribe::contact' => array(),
				'unsubscribe::success' => array(),
				'form::frame' => array(),
				'form::update' => array(),
				'contacts::form' => array(),
				'contacts::activate' => array(),
				'contacts::update' => array(),
				'share::results' => array(),
				'apistatistics::mailpublicopens' => array(),
				'apistatistics::mailpublicclicks' => array(),
				'apistatistics::mailpublicunsubscribed' => array(),
				'apistatistics::mailpublicspam' => array(),
				'apistatistics::mailpublicbounced' => array(),
				
				
				//Dashboard
				'index::index' => array('dashboard' => array('read')),
				//Account controller
				'account::index' => array('account' => array('read')),
				'account::accounting' => array('account' => array('billing')),
				'account::new' => array('account' => array('create', 'read')),
				'account::edit' => array('account' => array ('read', 'update')),
				'account::show' => array('user' => array ('read')),
				'account::delete' => array('account' => array ('read', 'delete')),
				'account::newuser' => array('user' => array ('read', 'create'),
									        'account' => array('read')),
				'account::edituser' => array('user' => array ('read','update'),
					                         'account' => array('read')),
				'account::deleteuser' => array('user' => array ('read', 'delete'),
					                           'account' => array('read')),
				//Contactlist controller
				'contactlist::index' => array('contactlist' => array('read')),
				'contactlist::show' => array('contactlist' => array('read')),
				//Contacts controller
				'contacts::index' => array('contact' => array('read')),
				'contacts::search' => array('contact' => array('read')),
				'contacts::newbatch' => array('contact' => array('read','importbatch')),
				'contacts::importbatch' => array('contact' => array('read', 'importbatch')),
				'contacts::import' => array('contact' => array('read','importbatch')),
				'contacts::processfile' => array('contact' => array('read','importbatch')),
				'process::import' => array('contact' => array('read', 'import')),
				'process::refreshimport' => array('contact' => array('read', 'import')),
				'process::downoladsuccess' => array('contact' => array('read', 'import')),
				'process::downoladerror' => array('contact' => array('read', 'import')),
				//Dbase controller
				'dbase::index' => array('dbase' => array('read')),
				'dbase::new' => array('dbase' => array('read','create')),
				'dbase::show' => array('dbase' => array('read')),
				'dbase::edit' => array('dbase' => array('read','update')),
				'dbase::delete' => array('dbase' => array('read', 'delete')),
				//Usuarios
				'user::index' => array('user' => array('read')),
				'user::new' => array('user' => array('read', 'create')),
				'user::edit' => array('user' => array('read', 'update')),
				'user::delete' => array('user' => array('read', 'delete')),
				//Api
				//Listas de contactos y contactos 
				'api::getlists' => array('contactlist' => array('read')),
				'api::createcontactlist' => array('contactlist' => array('read', 'create')),
				'api::dbaselist' => array('contactlist' => array('read', 'create')),
				'account::loadcontactsinfo' => array('contactlist' => array('read')),
				'api::listcontactsbylist' => array('contactlist' => array('read')),
				'api::getcontactbylist' => array('contact' => array('read')),
				'api::listbylist' => array('contactlist' => array('read')),
				'api::updatecontactbylist' => array('contact' => array('read','update')),
				'api::createcontactbylist' => array('contact' => array('read', 'create')),
				'api::deletecontactbylist' => array('contact' => array('read', 'delete')),
				'api::listsedit' => array('contactlist' => array('read', 'update')),
				'api::deletecontactlist' => array('contactlist' => array('read', 'delete')),
				//listas de bloqueo
				'api::listblockedemails' => array('blockemail' => array('read')),
				'api::addemailtoblockedlist' => array('blockemail' => array('read', 'block email')),
				'api::removeemailfromblockedlist' => array('blockemail' => array('read', 'unblock email')),
				//Campos personalizados
				'api::listcustomfields' => array('customfield' => array('read')),
				'api::createcustomfield' => array('customfield' => array('read', 'create')),
				'api::updatecustomfield' => array('customfield' => array('read', 'update')),
				'api::delcustomfield' => array('customfield' => array('read', 'delete')),
				//Contactos desde base de datos
				'api::listcontacts' => array('contact' => array('read')),
				'api::getcontact' => array('contact' => array('read')),
				'api::updatecontact' => array('contact' => array('read', 'update')),
				'api::deletecontact' => array ('contact' => array('read', 'delete')),
				'api::getonelist' => array('contactlist' => array('read')),
				//Búsqueda de contactos en general
				'api::searchcontact' => array('contact' => array('read')),
				//Segmentos 
				'segment::show' => array('segment' => array('read')),
				'api::listsegments' => array('segment' => array('read')),
				'api::listcontactsbysegment' => array('segment' => array('read')),
				'api::getcontactbysegment' => array('segment' => array('read')),
				'api::dbases' => array('segment' => array('read', 'update')),
				'api::getcustomfieldsalias' => array('segment' => array('create')),
				'api::createsegment' => array('segment' => array('read', 'create')),
				'api::deletesegment' => array('segment' => array('read', 'delete')),
				'api::updatesegment' => array('segment' => array('read', 'update')),
				'api::updatecontactbysegment' => array('contact' => array('read', 'update')),
				
				//Dbaseapi
				'dbaseapi::searchcontacts' => array('contact' => array('read')),
				
				//Dbaseapi -- Formularios
				'dbaseapi::getforms' => array('form' => array('read')),
				'dbaseapi::getforminformation' => array('form' => array('read')),
				'dbaseapi::createforminformation' => array('form' => array('create')),
				'dbaseapi::createformcontent' => array('form' => array('update')),
				'dbaseapi::deleteform' => array('form' => array('delete')),
				'dbaseapi::getcontactlists' => array('form' => array('read')),
				
                //Contactlistapi
				'contactlistapi::searchcontacts' => array('contact' => array('read')),
				//Segmentapi
				'segmentapi::searchcontacts' => array('contact' => array('read')),
				
				
				//Apistatistics Estadisticas
				'apistatistics::dbase' => array('statistic' => array('read')),
				'apistatistics::contactlistopens' => array('statistic' => array('read')),
				'apistatistics::mailopens' => array('statistic' => array('read')),
				'apistatistics::mailclicks' => array('statistic' => array('read')),
				'apistatistics::mailunsubscribed' => array('statistic' => array('read')),
				'apistatistics::mailspam' => array('statistic' => array('read')),
				'apistatistics::mailbounced' => array('statistic' => array('read')),
				'apistatistics::dbaseopens' => array('statistic' => array('read')),
				'apistatistics::dbaseclicks' => array('statistic' => array('read')),
				'apistatistics::dbaseunsubscribed' => array('statistic' => array('read')),
				'apistatistics::comparemailopens' => array('statistic' => array('read')),
				'apistatistics::comparemailclicks' => array('statistic' => array('read')),
				'apistatistics::comparemailunsubscribed' => array('statistic' => array('read')),
				'apistatistics::comparemailbounced' => array('statistic' => array('read')),
				'apistatistics::comparemailspam' => array('statistic' => array('read')),

			
		//* RELEASE 0.2.0 *//
				//Envío de correos
				'mail::index' => array('mail' => array('read')),
				'mail::list' => array('mail' => array('read')),
				'mail::setup' => array('mail' => array('read', 'create')),
				'mail::savetmpdata' => array('mail' => array('read', 'create')),
				'mail::savecontent' => array('mail' => array('read', 'create')),
				'mail::source' => array('mail' => array('read', 'create')),
				'mail::editor' => array('mail' => array('read', 'create')),
				'mail::html' => array('mail' => array('read', 'create')),
				'mail::contenthtml' => array('mail' => array('read', 'create')),
				'mail::contenteditor' => array('mail' => array('read', 'create')),
				'mail::target' => array('mail' => array('read', 'create')),
				'mail::track' => array('mail' => array('read', 'create')),
				'mail::schedule' => array('mail' => array('read', 'create')),
				'mail::delete' => array('mail' => array('read', 'delete')),
				'mail::import' => array('mail' => array('read', 'create')),
				'mail::importcontent' => array('mail' => array('read', 'create')),
				'mail::clone' => array('mail' => array('read', 'clone')),
				'asset::upload' => array('mail' => array('read', 'create')),
				'asset::show' => array('mail' => array('read', 'create')),
				'asset::list' => array('mail' => array('read', 'create')),
				'asset::thumbnail' => array('mail' => array('read', 'create')),
				'mail::editor_frame' => array('mail' => array('read', 'create')),
				'mail::plaintext' => array('mail' => array('read', 'create')),
				'mail::filter' => array('mail' => array('read', 'create')),
				'mail::preview' => array('mail' => array('read', 'create', 'send')),
				'mail::previeweditor' => array('mail' => array('read', 'create', 'send')),
				'mail::template' => array('mail' => array('read', 'create')),
				'mail::converttotemplate' => array('mail' => array('read', 'create')),
				'mail::confirm' => array('mail' => array('read', 'create')),
				'mail::confirmmail' => array('mail' => array('send')),
				'mail::play' => array('mail' => array('read', 'send')),
				'mail::stop' => array('mail' => array('read', 'send')),
				'mail::cancel' => array('mail' => array('read', 'send')),
				'mail::sendtest' => array('mail' => array('read', 'send')),
				'mail::checkforms' => array('mail' => array('read', 'send')),
				
				'mail::compose' => array('mail' => array('read', 'create', 'send')),
				
				//Plantillas
				'template::image' => array('template' => array('read')),
				'template::thumbnail' => array('template' => array('read')),
				'template::create' => array('template' => array('create')),
				'template::preview' => array('template' => array('read')),
				'template::previewtemplate' => array('template' => array('read')),
				'template::createpreview' => array('template' => array('read', 'create')),
				'template::previewdata' => array('template' => array('read')),
				'template::index' => array('template' => array('read')),
				'template::select' => array('template' => array('read')),
				'template::new' => array('template' => array('create')),
				'template::edit' => array('template' => array('update')),
				'template::delete' => array('template' => array('delete')),
				'template::editor_frame' => array('template' => array('read', 'create')),
				'template::edit' => array('template' => array('read', 'update')),
				//Fin plantillas
				
				'mail::previewmail' => array('mail' => array('read', 'create', 'send')),
				'mail::previewtemplate' => array('mail' => array('read', 'create', 'send')),
				'mail::previewdata' => array('mail' => array('read', 'create', 'send')),
				'mail::previewindex' => array('mail' => array('read', 'create', 'send')),
				'mail::previewhtml' => array('mail' => array('read', 'create')),
				
				//Processes
				'process::index' => array('process' => array('read')),
				'process::importdetail' => array('process' => array('read')),
				'process::getprocesses' => array('process' => array('read')),
				'process::stopsending' => array('process' => array('read')),
				'process::stopimport' => array('process' => array('read')),
				
				//Programming mail
				'scheduledmail::index' => array('mail' => array('read', 'create', 'send')),
				'scheduledmail::stop' => array('mail' => array('read', 'create', 'send')),
				'scheduledmail::play' => array('mail' => array('read', 'create', 'send')),
				'scheduledmail::cancel' => array('mail' => array('read', 'create', 'send')),
				'scheduledmail::manage' => array('mail' => array('manage')),

				//tests
				'test::start' => array('mail' => array('read', 'create', 'send')),
				'test::testemailcontact' => array('mail' => array('read', 'create', 'send')),
				'test::mailer' => array('mail' => array('read', 'create', 'send')),
				'test::aperturas' => array('statistic' => array('read')),
				'test::assettest' => array('statistic' => array('read')),
				'test::transaction' => array('statistic' => array('read')),
				'test::organizelinks' => array('statistic' => array('read')),
				'test::facebooktest' => array('mail' => array('read')),
				'test::facebookposting' => array('mail' => array('read')),
				'test::twittertest' => array('mail' => array('read')),
				'test::imagetest' => array('mail' => array('read')),
				'test::unsubscribed' => array('mail' => array('read')),
				'test::testsnimageresize' => array('mail' => array('read')),
				'test::testoptinmail' => array('form' => array('create')),
				
				//statistics
				'statistic::index' => array('statistic' => array('read')),
				'statistic::show' => array('statistic' => array('read')),
				'statistic::dbase' => array('statistic' => array('read')),
				'statistic::contactlist' => array('statistic' => array('read')),
				'statistic::mail' => array('statistic' => array('read')),
				'statistic::downloadreport' => array('statistic' => array('download')),
				'statistic::comparemails' => array('statistic' => array('read')),
				'statistic::comparelists' => array('statistic' => array('read')),
				'statistic::comparedbases' => array('statistic' => array('read')),
				
				'share::statistics' => array('statistic' => array('share')),
				
				//flash messages
				'flashmessage::index' => array('flashmessage' => array('read')),
				'flashmessage::new' => array('flashmessage' => array('create')),
				'flashmessage::edit' => array('flashmessage' => array('update')),
				'flashmessage::delete' => array('flashmessage' => array('delete')),
				
				//google analytics
				'mail::analytics' => array('mail' => array('read', 'create')),
				
				//Redes Sociales
				'socialmedia::index' => array('socialmedia' => array('read')),
				'socialmedia::new' => array('socialmedia' => array('create')),
				'socialmedia::create' => array('socialmedia' => array('create')),
				'socialmedia::delete' => array('socialmedia' => array('delete')),
				
				//Sistema
				'system::index' => array('system' => array('read')),
				'system::configure' => array('system' => array('update')),

				// Herramientas de administracion
				'tools::index' => array('tools' => array('read')),
				'mail::savemail' => array('mail' => array('create')),
				
				//Formularios
				'form::preview' => array('form' => array('read')),
				
				//Footer
				'footer::preview' => array('footer' => array('read')),
				'footer::new' => array('footer' => array('create')),
				'footer::previeweditor' => array('footer' => array('read')),
				'footer::previewdata' => array('footer' => array('read')),
				'footer::index' => array('footer' => array('read')),
				
			);
		}
		$this->cache->save('controllermap-cache', $map);
		return $map;
	}
	
	public function setJsonResponse($content, $status, $message) {
		$this->view->disable();

		$this->_isJsonResponse = true;
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setStatusCode($status, $message);
		
		if (is_array($content)) {
			$content = json_encode($content);
		}
		$this->response->setContent($content);
		return $this->response;
	}
	
	protected function validateResponse($controller, $action = null){
		$controllersWithjsonResponse = array ('api');
		if(in_array($controller, $controllersWithjsonResponse)){
			return true;
		}
		return false;
	}

	/**
	 * This action is executed before execute any action in the application
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{
		$controller = strtolower($dispatcher->getControllerName());
		$action = strtolower($dispatcher->getActionName());
		$resource = "$controller::$action";
		
		$this->logger->log("Server Status: {$this->serverStatus}");
		$this->logger->log("Allowed Ip's: ". print_r($this->allowed_ips, true));
		$this->logger->log("Ip: {$this->ip}");
		
		if ($this->serverStatus == 0 && !in_array($this->ip, $this->allowed_ips)) {
			$this->publicurls = array(
				'error:index',
				'error:link',
				'error:notavailable',
				'error:unauthorized',
			);
			$accessdir = $controller . ':' . $action;
			if (!in_array($accessdir, $this->publicurls)) {
				return $this->response->redirect('error/notavailable');
			}
			return false;
		}
		
		$role = 'ROLE_GUEST';
		if ($this->session->get('authenticated')) {
			$user = User::findFirstByIdUser($this->session->get('userid'));
			if ($user) {
				$role = $user->userrole;
				// Inyectar el usuario
				$this->_dependencyInjector->set('userObject', $user);
				
				$userefective = new stdClass();
				$userefective->enable = false;
				
				$efective = $this->session->get('userefective');
				if (isset($efective)) {
					$userefective->enable = true;
				}
				
				$this->_dependencyInjector->set('userefective', $userefective);
			}
		}

		$map = $this->getControllerMap();
		
		$this->publicurls = array(
			'session:signin', 
			'session:login',
			'session:logout',
			'session:recoverpass',
			'session:setnewpass',
			'session:logoutfromthisaccount',
			'session:reset',
			'error:index',
			'error:link',
			'error:notavailable',
			'error:unauthorized',
			'track:open',
			'track:click',
			'track:mtaevent',
			'track:opensocial',
			'track:clicksocial',
			'webversion:show',
			'webversion:share',
			'socialmedia:share',
			'unsubscribe:contact',
			'unsubscribe:success',
			'form:frame',
			'form:update',
			'contacts:form',
			'contacts:activate',
			'contacts:update',
			'share:results',
			'apistatistics:mailpublicopens',
			'apistatistics:mailpublicclicks',
			'apistatistics:mailpublicunsubscribed',
			'apistatistics:mailpublicspam',
			'apistatistics:mailpublicbounced'
		);
		
		if ($resource == "error::notavailable") {
			$this->response->redirect('index');
			return false;
		}

		if ($role == 'ROLE_GUEST') {
			$accessdir = $controller . ':' . $action;
			
			if (!in_array($accessdir, $this->publicurls)) {
					$this->response->redirect("session/signin");
					return false;
			}
		}
		else{
			$acl = $this->getAcl();
			$this->logger->log("Validando el usuario con rol [$role] en [$resource]");
			
			
			if (!isset($map[$resource])) {
				if($this->validateResponse($controller) == true){
					$this->logger->log("Accion no permitida accesando desde ember");
					$this->logger->log("Controller: {$controller}, Action: {$action}");
					$this->setJsonResponse(array('status' => 'deny'), 404, 'Accion no permitida');
				}
				else{
					$this->logger->log("Redirect to error");
					// Uso forward para que la URL se mantenga, y así el usuario pueda
					// saber cual es la que da problemas
					$dispatcher->forward(array('controller' => 'error', 'action' => 'index'));
				}
				return false;
			}


			$reg = $map[$resource];
			
			foreach($reg as $resources => $actions){
				foreach ($actions as $act) {
					if (!$acl->isAllowed($role, $resources, $act)) {
						$this->logger->log('Accion no permitida');
						$this->logger->log("Controller: {$controller}, Action: {$action}");
//						$this->logger->log(print_r($acl, true));
						if($this->validateResponse($controller) == true){
							$this->logger->log('Accion no permitida accesando desde ember');
							$this->logger->log("Controller: {$controller}, Action: {$action}");
							$this->setJsonResponse('Denegado', 404, 'Accion no permitida');
						}
						else{
							// Uso forward para que la URL se mantenga, y así el usuario pueda
							// saber cual es la que da problemas
							$dispatcher->forward(array('controller' => 'error', 'action' => 'unauthorized'));
						}
						return false;
					}
				}
			}
			
			$mapForLoginLikeAnyUser = array('session::loginlikethisuser');
			
			if (in_array($resource, $mapForLoginLikeAnyUser)) {
				$this->session->set('userefective', $user);
			}
			
			return true;
		}
	}	
}
