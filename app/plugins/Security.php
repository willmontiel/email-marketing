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

			//Register roles
			$roles = array(
				'ROLE_ADMIN' => new Phalcon\Acl\Role('ROLE_ADMIN'),
				'ROLE_CONTACT' => new Phalcon\Acl\Role('ROLE_CONTACT'),
				'ROLE_SUDO' => new Phalcon\Acl\Role('ROLE_SUDO')
			);
			
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
			
			
			//Roles de usuario
			$ROLE_ADMIN = array(
				'user' => array('index', 'new', 'edit', 'delete', 'show'),
				'dbase' => array('index', 'edit', 'new', 'show', 'delete'),
				'contactlist' => array('index', 'new', 'index', 'show'),
				'contacts' => array('newbatch'),
				'field' => array('index', 'insert','new', 'update', 'query', 'edit', 'delete'),
			);
			
			$ROLE_CONTACT = array(
				'contactlist' => array('index', 'new', 'index', 'show'),
				'contacts' => array('newbatch', 'processfile', 'import', 'importbatch'),
			);
			
			$ROLE_SUDO = array(
				'account' => array('show', 'new', 'edit', 'delete', 'list', 'newuser', 'edituser', 'deleteuser', 'index'),
				'user' => array('index', 'new', 'edit', 'delete', 'show'),
				'dbase' => array('index', 'edit', 'new', 'show', 'delete'),
				'contactlist' => array('index', 'new', 'index', 'show'),
				'contacts' => array('newbatch', 'processfile', 'import', 'importbatch'),
				'field' => array('index','insert', 'new', 'update', 'query', 'edit', 'delete'),
			);

			
			foreach ($ROLE_ADMIN as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}
			//Grant acess to private area to ROLE_ADMIN
			foreach ($ROLE_ADMIN as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('ROLE_ADMIN', $resource, $action);
				}
			}
			
			foreach ($ROLE_SUDO as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}
			//Grant acess to private area to ROLE_SUDO
			foreach ($ROLE_SUDO as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('ROLE_SUDO', $resource, $action);
				}
			}
			
			foreach ($ROLE_CONTACT as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}
			//Grant acess to private area to ROLE_SUDO
			foreach ($ROLE_CONTACT as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('ROLE_CONTACT', $resource, $action);
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
		
		$this->publicurls = array('session:signin', 'session:login', 'field:insert', 'field:update', 
								  'field:query', 'api:listcontacts', 'api:getcontact', 
								  'api:createcontact', 'api:updatecontact', 'api:delcustomfield',
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
