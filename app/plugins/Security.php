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

	public function __construct($dependencyInjector)
	{
		$this->_dependencyInjector = $dependencyInjector;
	}

	public function getAcl()
	{
		/*
		 * Buscar ACL en cache
		 */
		$acl = null; //$this->cache->get('acl-cache');
		
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
		$map = null; //$this->cache->get('controllermap-cache');
		if (!$map) {
			$map = array(
		//* RELEASE 0.1.0 *//
				//Tests
				'test::transactionsegment' => array(),				
				
				'error::index' => array(),
				'session::signin' => array(),
				'session::login' => array(),
				'session::logout' => array(),
				//Dashboard
				'index::index' => array('dashboard' => array('read')),
				//Account controller
				'account::index' => array('account' => array('read')),
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
				'contacts::newbatch' => array('contact' => array('read','importbatch')),
				'contacts::importbatch' => array('contact' => array('read', 'importbatch')),
				'contacts::import' => array('contact' => array('read','importbatch')),
				'contacts::processfile' => array('contact' => array('read','importbatch')),
				//Página de procesos
				'proccess::show' => array('process' => array('read')),
				'proccess::downoladsuccess' => array('process' => array('download')),
				'proccess::downoladerror' => array('process' => array('download')),
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
				//Segmentos 
				'segment::show' => array('segment' => array('read')),
				'api::segment' => array('segment' => array('read')),
				'api::segments' => array('segment' => array('read')),
				'api::dbases' => array('segment' => array('read', 'update')),
				'api::getcustomfieldsalias' => array('segment' => array('create')),
				'api::createsegment' => array('segment' => array('read', 'create')),
				'api::deletesegment' => array('segment' => array('read', 'delete')),
				'api::updatesegment' => array('segment' => array('read', 'update')),
				'api::updatecontactbysegment' => array('contact' => array('read', 'update')),
			
		//* RELEASE 0.2.0 *//
				//Envío de correos
				'mail::index' => array('mail' => array('read')),
				'mail::setup' => array('mail' => array('read', 'create')),
				'mail::source' => array('mail' => array('read', 'create')),
				'mail::editor' => array('mail' => array('read', 'create')),
				'mail::html' => array('mail' => array('read', 'create')),
				'mail::target' => array('mail' => array('read', 'create')),
				'mail::schedule' => array('mail' => array('read', 'create')),
				'mail::delete' => array('mail' => array('read', 'delete')),
				'mail::import' => array('mail' => array('read', 'create')),
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
				'template::image' => array('mail' => array('read', 'create')),
				'template::thumbnail' => array('mail' => array('read', 'create')),
				'template::preview' => array('mail' => array('read', 'create')),
				'template::new' => array('template' => array('read', 'create')),
				'template::editor_frame' => array('template' => array('read', 'create')),
				'template::edit' => array('template' => array('read', 'update')),
				'template::preview' => array('mail' => array('read', 'create', 'send')),
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
		$role = 'ROLE_GUEST';
		if ($this->session->get('authenticated')) {
			$user = User::findFirstByIdUser($this->session->get('userid'));
			if ($user) {
				$role = $user->userrole;
				// Inyectar el usuario
				$this->_dependencyInjector->set('userObject', $user);
			}
		}

		$map = $this->getControllerMap();
		
		$this->publicurls = array(
			'session:signin', 
			'session:login',
			'session:logout'
		);
		
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		if ($role == 'ROLE_GUEST') {
			$accessdir = $controller . ':' . $action;
			
			if (!in_array($accessdir, $this->publicurls)) {
					$this->response->redirect("session/signin");
					return false;
			}
		}
		else{
			$acl = $this->getAcl();
			
			if (!isset($map[$controller .'::'. $action])) {
				if($this->validateResponse($controller) == true){
					$this->setJsonResponse(array('status' => 'deny'), 404, 'Acción no permitida');
				}
				else{
					$this->response->redirect('error');
				}
				return false;
			}


			$reg = $map[$controller .'::'. $action];
			
			foreach($reg as $resources => $actions){
				foreach ($actions as $act) {
					if (!$acl->isAllowed($role, $resources, $act)) {
						$this->logger->log(print_r($acl, true));
						if($this->validateResponse($controller) == true){
							$this->setJsonResponse('Denegado', 404, 'Acción no permitida');
						}
						else{
							$this->response->redirect('error');
						}
						return false;
					}
				}
			}
			return true;
		}
	}	
}
