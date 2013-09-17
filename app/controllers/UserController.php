<?php

class UserController extends ControllerBase 
{
	public function indexAction()
	{
		$r = $this->verifyAcl('user', 'index', '');
		if ($r)
			return $r;
		
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
		
		$this->view->setVar("page", $page);
	}
	
	public function newAction()
	{
		$r = $this->verifyAcl('user', 'new', '');
		if ($r)
			return $r;
		
		$user = new User();
		$form = new NewUserForm($user);
		
		if($this->request->isPost()) {
			$form->bind($this->request->getPost(), $user);
			
			$pass = $form->getValue('password');
			$pass2 = $form->getValue('password2');
			
			if(strlen($pass) < 8 || strlen($pass) > 40) {
				$this->view->disable();
				$this->flashSession->error("La contraseña es muy corta, esta debe tener mínimo 8 y máximo 40 caracteres");
				$this->response->redirect("user/new");
			}
			else {
				if($pass !== $pass2) {
					$this->view->disable();
					$this->flashSession->error("Las contraseñas no coinciden por favor verifica la información");
					$this->response->redirect("user/new");
				}
				else {
					
					$this->db->begin();
					$user->idAccount = $this->user->account->idAccount;
					$user->password = $this->security2->hash($pass);
				
					if ($form->isValid() && $user->save()) {
						$this->db->commit();
						$this->flashSession->success("Se ha creado el usuario exitosamente");
						$this->view->disable();
						$this->response->redirect("user/index");
					}
					
					else {
						$this->db->rollback();
						foreach ($user->getMessages() as $msg) {
							$this->flashSession->error($msg);
						}
						$this->view->disable();
						$this->response->redirect("user/index");
					}
				}
			}
		}
		
		$this->view->NewUserForm = $form;
	}
	
	public function editAction($id)
	{
		$r = $this->verifyAcl('user', 'edit', '');
		if ($r)
			return $r;
		
		$idAccount = $this->user->account->idAccount;
		
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 AND idAccount = ?2",
			"bind" => array(1 => $id, 2 => $idAccount)
		));
		
		if (!$user) {
			$this->view->disable();
			$this->flashSession->error("El usuario que intenta actualizar no existe, por favor verifique la información");
			$this->response->redirect("user/index");
		}
		
		else {
			$form = new NewUserForm($user);
			
			if ($this->request->isPost()) {   
				
				$form->bind($this->request->getPost(), $user);
				
				$pass = $form->getValue('pass');
				$pass2 = $form->getValue('password2');
				
				if(strlen($pass) < 8 || strlen($pass) > 40) {
					$this->flashSession->error("La contraseña es muy corta o muy larga, esta debe tener mínimo 8 y máximo 40 caracteres");
					$this->view->disable();
					$this->response->redirect("user/edit/".$user->idUser);
				}
				else{
					if($pass !== $pass2) {
						$this->flashSession->error("Las contraseñas no coinciden por favor verifica la información");
						$this->view->disable();
						$this->response->redirect("user/edit/".$user->idUser);
					}
					else{
						$this->db->begin();
						if (!$form->isValid() OR !$user->save()) {
							$this->db->rollback();
							foreach ($user->getMessages() as $msg) {
								$this->flash->error($msg);
							}
							$this->view->disable();
							$this->response->redirect("user/edit/".$user->idUser);
						}
						else {
							$this->db->commit();
							$this->flashSession->success('Se ha actualizado el usuario exitosamente');
							$this->view->disable();
							$this->response->redirect("user/index");
						}
					}
				}
 			}
			$this->view->setVar("user", $user);
			$this->view->NewUserForm = $form;
		}
	}
	
	public function deleteAction($id)
	{
		$r = $this->verifyAcl('user', 'delete', '');
		if ($r)
			return $r;
		
		$idAccount = $this->user->account->idAccount;
		
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1 AND idAccount = ?2",
			"bind" => array(1 => $id, 2 => $idAccount)
		));
		
		if($user){
			if(!$user->delete()){
				foreach ($user->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
			}
			$this->flashSession->success("El usuario " .$user->username. " ha sido borrado exitosamente");
			$this->view->disable();
			$this->response->redirect("user/index");
		}
		else{
			$this->flashSession->error("El usuario que intenta borrar no existe, por favor verifique la información");
			$this->view->disable();
			$this->response->redirect("user/index");
		}
	}
}