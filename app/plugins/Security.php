<?php

use Phalcon\Events\Event,
	Phalcon\Mvc\User\Plugin,
	Phalcon\Mvc\Dispatcher,
	Phalcon\Acl;
/**
 * Security
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
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
				$k = $key['resource'];
				$v = $key['action'];
				
				if(!isset($resources[$k])){
					$resources[$k];
				}
				$resources[$k][] = $v;
			}
			
			foreach ($resources as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}
			//Grant acess to private area to ROLE_ADMIN
			foreach ($resources as $resource => $actions) {
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
