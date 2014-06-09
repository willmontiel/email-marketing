<?php

class UserController extends ControllerBase 
{
	public function indexAction()
	{
		$idAccount = $this->user->account->idAccount;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => User::find("idAccount = $idAccount"),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);
		
		$page = $paginator->getPaginate();
		
		$allow = $this->verifyAcl('user', 'new');
		
		$this->view->setVar("page", $page);
		$this->view->setVar("allow", $allow);
	}
	
	public function newAction()
	{
		$user = new User();
		$form = new UserForm($user);
		
		$account = $this->user->account;
		$prefix = $account->prefix . '_';
		$this->view->setVar('prefix', $prefix);
		
		if($this->request->isPost()) {
			$form->bind($this->request->getPost(), $user);
			
			$username = $form->getValue('username');
			
			if (strlen($username) < 4) {
				$this->flashSession->error("El nombre de usuario es muy corto, debe tener al menos 4 caracteres");
			}
			else {
				$user->username = $prefix . $username;
				$pass = $form->getValue('password');
				$pass2 = $form->getValue('password2');
				$email = strtolower($form->getValue('email'));

				if(strlen($pass) < 8 || strlen($pass) > 40) {
					$this->flashSession->error("La contraseña es muy corta, esta debe tener mínimo 8 y máximo 40 caracteres");
				}
				else {
					if($pass !== $pass2) {
						$this->flashSession->error("Las contraseñas no coinciden por favor verifique la información");
					}
					else {
						$user->idAccount = $account->idAccount;
						$user->email = $email;
						$user->password = $this->security2->hash($pass);

						if ($form->isValid() && $user->save()) {
							$this->flashSession->notice("Se ha creado el usuario exitosamente");
							$this->traceSuccess("Create user, account {$account->idAccount}");
							return $this->response->redirect("user/index");
						}
						else {
							foreach ($user->getMessages() as $msg) {
								$this->flashSession->error($msg);
							}
						}
					}
				}
			}
		}
		
		$this->view->UserForm = $form;
	}
	
	
	protected function removePrefix($username)
	{
		$un = explode('_', $username);
		
		if (isset($un[1])) {
			return $un[1];
		}
		return $username;
	}
	
	public function editAction($id)
	{
		$account = $this->user->account;
		
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 AND idAccount = ?2",
			"bind" => array(1 => $id, 2 => $account->idAccount)
		));
		
		if (!$user) {
			$this->flashSession->error("El usuario que intenta editar no existe, por favor verifique la información");
			return $this->response->redirect("user/index");
		}
		
		$prefix = $account->prefix . '_';
		$this->view->setVar('prefix', $prefix);
		
//		$user->username = $this->removePrefix($user->username);
		$form = new UserForm($user);

		if ($this->request->isPost()) {   
			$form->bind($this->request->getPost(), $user);
			
			$username = $form->getValue('username');
			
			if (strlen($username) < 4) {
				$this->flashSession->error("El nombre de usuario es muy corto, debe tener al menos 4 caracteres");
			}
			else {
				$user->username = $prefix . $username;
				$pass = $form->getValue('passForEdit');
				$pass2 = $form->getValue('pass2ForEdit');
				$email = strtolower($form->getValue('email'));

				if(!empty($pass)||!empty($pass2)){
					if(strlen($pass) < 8 || strlen($pass) > 40) {
						$this->flashSession->error("La contraseña es muy corta o muy larga, esta debe tener mínimo 8 y máximo 40 caracteres");
					}
					else{
						if($pass !== $pass2) {
							$this->flashSession->error("Las contraseñas no coinciden por favor verifique la información");
						}
						else{
							$user->password = $this->security2->hash($pass);
							$user->email = $email;

							if (!$form->isValid()||!$user->save()) {
								foreach ($user->getMessages() as $msg) {
									$this->flashSession->error($msg);
								}
							}
							else {
								$this->traceSuccess("Edit user: {$id}, account {$account->idAccount}");
								$this->flashSession->notice('Se ha actualizado el usuario exitosamente');
								return $this->response->redirect("user");
							}
						}
					}
				}
				else{
					$user->email = $email;

					if (!$form->isValid() OR !$user->save()) {
						foreach ($user->getMessages() as $msg) {
							$this->flashSession->error($msg);
						}
					}
					else {
						$this->flashSession->notice('Se ha actualizado el usuario exitosamente');
						$this->traceSuccess("Edit user: {$id}, account {$account->idAccount}");
						return $this->response->redirect("user/index");
					}
				}
			}
		}
		$this->view->setVar("user", $user);
		$this->view->UserForm = $form;
	}
	
	public function deleteAction($id)
	{
		$idUser = $this->session->get('userid');
		
		if($id == $idUser){
			$this->traceFail("Trying to delete an user in session, idUser: {$id} - account: {$this->user->account->idAccount}");
			$this->flashSession->error("No se puede eliminar el usuario que esta actualmente en sesión, por favor verifique la información");
			return $this->response->redirect("user/index");
		}
		
		$account = $this->user->account;
		
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 AND idAccount = ?2",
			"bind" => array(1 => $id, 2 => $account->idAccount)
		));

		if($user){
			if(!$user->delete()){
				foreach ($user->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
				return $this->response->redirect("user/index");
			}
			$this->traceSuccess("User deleted, idUser: {$id} - account: {$account->idAccount}");
			$this->flashSession->warning("El usuario <strong>" .$user->username. "</strong> ha sido eliminado exitosamente");
			return $this->response->redirect("user/index");
		}
		else{
			$this->traceFail("Trying to delete a user that dont exists idUser: {$id}, account: {$account->idAccount}");
			$this->flashSession->error("El usuario que intenta borrar no existe, por favor verifique la información");
			return $this->response->redirect("user/index");
		}
	}
}