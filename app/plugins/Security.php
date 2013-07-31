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
				'ROLE_USER' => new Phalcon\Acl\Role('ROLE_USER')
			);
			
			foreach ($roles as $role) {
				$acl->addRole($role);
			}

			//Private area resources
			$privateResources = array(
				'account' => array('show', 'new', 'edit', 'delete', 'list'),
				'dbase' => array('list', 'edit', 'new', 'show'),
				'field' => array('new'),
			);

			foreach ($privateResources as $resource => $actions) {
				$acl->addResource(new Phalcon\Acl\Resource($resource), $actions);
			}

			//Grant acess to private area to role Users
			foreach ($privateResources as $resource => $actions) {
				foreach ($actions as $action){
					$acl->allow('ROLE_ADMIN', $resource, $action);
					$acl->allow('ROLE_USER', $resource, $action);
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
		
		$this->publicurls = array('session:signin', 'session:login', 'field:insert', 'field:update', 'field:query');

		if ($role == 'ROLE_GUEST') {
			$accessdir = $controller . ':' . $action;
			if (!in_array($accessdir, $this->publicurls)) {
				$this->response->redirect("session/signin");
				return false;
			}
		}
		
		$this->_dependencyInjector->set('acl', $this->getAcl());

	}

}
