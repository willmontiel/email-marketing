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
			
			$roles = array();
			foreach ($userroles as $role){
				$roles[$role->name] = new Phalcon\Acl\Role($role->name);
			}
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
			
			$db = Phalcon\DI::getDefault()->get('db');
			
			$sql = "SELECT resource.name AS resource, roxre.action AS action 
					FROM roxre
						JOIN resource ON ( roxre.idResource = resource.idResource )";
			
			$results = $db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC);
			
			$resources = array();
			foreach ($results as $key) {
				
				if(!isset($resources[$key['resource']])){
					$resources[$key['resource']];
				}
				$resources[$key['resource']][] = $key['action'];
			}
			
			foreach ($resources as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}
			
//--------------------------------------------------------------------------------------------------------------
			
			$sql2 ="SELECT resource.name AS resource, roxre.action AS action 
					FROM allowed
						JOIN roxre ON ( allowed.idRoxre = roxre.idRoxre ) 
						JOIN resource ON ( roxre.idResource = resource.idResource ) 
					WHERE allowed.idRole =1";
			
			$allowedResources = $db->fetchAll($sql2, Phalcon\Db::FETCH_ASSOC);
			
			//Grant acess to private area to ROLE_ADMIN
			$allow = array();
			
			foreach($allowedResources as $allowedResource){
				if(!isset($allow[$allowedResource['resource']])){
					$allow[$allowedResource['resource']];
				}
				$allow[$allowedResource['resource']][] = $allowedResource['action'];
			}
			
			//Grant acess to private area to ROLE_ADMIN
			foreach ($allow as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('ROLE_SUDO', $resource, $action);
				}
			}
	
			//The acl is stored in session, APC would be useful here too
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

		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();
		
		$this->publicurls = array(
			'session:signin', 
			'session:login'
		);
		
		if ($role == 'ROLE_GUEST') {
			$accessdir = $controller . ':' . $action;
			$this->logger->log("DIR: " . $accessdir);
			if (!in_array($accessdir, $this->publicurls)) {
				$this->response->redirect("session/signin");
				return false;
			}
		}
		
		$this->_dependencyInjector->set('acl', $this->getAcl());
	}
}
