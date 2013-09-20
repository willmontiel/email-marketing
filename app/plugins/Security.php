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
		if (!isset($this->persistent->acl)) {

			$acl = new Phalcon\Acl\Adapter\Memory();
			$acl->setDefaultAction(Phalcon\Acl::DENY);

			//Registrando roles
			$userroles = Role::find();
			foreach ($userroles as $role){
				$acl->addRole(new Phalcon\Acl\Role($role->name));
			}
			//Registrando recursos
			$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
			
			$sql = "SELECT Resource.name AS resource, Roxre.action AS action 
					FROM Roxre
						JOIN Resource ON ( Roxre.idResource = Resource.idResource )";
			
			$results = $modelManager->executeQuery($sql);
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
			
			$userandroles = $modelManager->executeQuery('SELECT Role.name AS rolename, Resource.name AS resname, Roxre.action AS actname
														 FROM Allowed
															JOIN Role ON ( Role.idRole = Allowed.idRole ) 
															JOIN Roxre ON ( Roxre.idRoxre = Allowed.idRoxre ) 
															JOIN Resource ON ( Roxre.idResource = Resource.idResource ) ');
			
			//Relacionando roles y recursos desde la base de datos
			foreach($userandroles as $role) {
				$acl->allow($role->rolename, $role->resname, $role->actname);
			}

			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
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
		
	//Este arreglo contiene los controladores y las acciones que son públicas como el inicio de sesión (session:signin)
		$this->publicurls = array(
			'session:signin', 
			'session:login',
			'session:logout'
		);
		
		$map = array(
			'index::index' => array('session' => array('login')),
			'error::index' => array(),
			'session::logout' => array('session' => array('login')),
			//Account controller
			'account::index' => array('account' => array('read')),
			'account::new' => array('account' => array('create', 'read')),
			'account::edit' => array('account' => array ('read', 'update')),
			'account::show' => array('user' => array ('read')),
			'account::delete' => array('account' => array ('read', 'delete')),
			'account::newuser' => array('user' => array ('read', 'create')),
			'account::edituser' => array('user' => array ('read','edit')),
			'account::deleteuser' => array('user' => array ('read', 'delete')),
			//Contactlist controller
			'contactlist::index' => array('contactlist' => array('read')),
			'contactlist::show' => array('contactlist' => array('read')),
			//Contacts controller
			'contacts::index' => array('contact' => array('read')),
			'contacts::newbatch' => array('contact' => array('importbatch')),
			'contacts::importbatch' => array('contact' => array('importbatch')),
			'contacts::processfile' => array('contact' => array('importbatch')),
			//Dbase controller
			'dbase::index' => array('dbase' => array('read')),
			'dbase::new' => array('dbase' => array('read','new')),
			'dbase::show' => array('dbase' => array('read')),
			'dbase::edit' => array('edit' => array('read','update')),
			'dbase::delete' => array('dbase' => array('read', 'delete')),
			//
			'user::index' => array('user' => array('read'))
		);
		
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		
		$this->logger->log("DIR: " . $controller . ':' . $action);
		$this->logger->log("ROLE: " . $role);
		
		if ($role == 'ROLE_GUEST') {
			$accessdir = $controller . ':' . $action;
			$this->logger->log("DIR: " . $accessdir);
			if (!in_array($accessdir, $this->publicurls)) {
				$this->response->redirect("session/signin");
				return false;
			}
		}
		else {
			$acl = $this->getAcl();

			$this->logger->log("Validando el usuario con rol [$role] en [$controller::$action]");
			if (!isset($map[$controller .'::'. $action])) {
				$this->logger->log("[$controller::$action] no existe en el mapa de permisos");
				$this->response->redirect('error/index');
				return false;
			}

			$reg = $map[$controller .'::'. $action];
			$this->logger->log("[$controller::$action] tiene este mapa: " . print_r($reg, true));

			foreach($reg as $resources => $actions){
				$this->logger->log("Validando permiso sobre recurso [$resources] (" . implode(',', $actions) . ")");
				foreach ($actions as $act) {
					if (!$acl->isAllowed($role, $resources, $act)) {
						$this->logger->log('Verificacion manual: ' . ($acl->isAllowed('ROLE_ADMIN', 'session', 'login')?'YES':'NO'));
						$this->logger->log("Oops no esta permitido: [$resources] ($act)");
						$this->logger->log(print_r($acl, true));
						$this->response->redirect('error/index');
						return false;
					}
				}
			}
			return true;
		}	
	}
}
