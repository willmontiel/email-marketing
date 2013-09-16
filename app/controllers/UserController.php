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
			
			if(strlen($pass) >= 8) {
				
				if($pass == $pass2) {
					
					$this->db->begin();
					$user->idAccount = $this->user->account->idAccount;
					$user->password = $this->security2->hash($pass);
					

					if ($form->isValid() && $user->save()) {
						$this->db->commit();
						$this->flashSession->success("Se ha creado el usuario exitosamente");
						$this->response->redirect("user");
					}
					
					else {
						$this->db->rollback();
						foreach ($user->getMessages() as $msg) {
							$this->flash->error($msg);
						}
					}
				}
				else {
					$this->flashSession->error("Las contraseñas no coinciden por favor verifica la información");
					$this->response->redirect("user/new");
				}
			}
			else {
				$this->flashSession->error("La contraseña es muy corta, debe estar entre 8 y 40 caracteres");
				$this->response->redirect("user/new");
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
		
		$user = User::findFirst("idUser = $id AND idAccount = $idAccount");
		
		if (!$user) {
			$this->flashSession->error("El usuario que intenta actualizar no existe");
			$this->response->redirect("user/index");
		}
		
		else {
            $this->view->setVar("allUser", $user);
			$form = new NewUserForm($user);
			
			if ($this->request->isPost()) {   
				
				$form->bind($this->request->getPost(), $user);
				
				$pass = $form->getValue('pass');
				$pass2 = $form->getValue('password2');
				
				if(strlen($pass) >= 8) {
					
					if($pass == $pass2) {
						$this->db->begin();
						if (!$form->isValid() OR !$user->save()) {
							$this->db->rollback();
							foreach ($user->getMessages() as $msg) {
								$this->flash->error($msg);
							}
						}
						else {
							$this->db->commit();
							$this->flash->success('Se ha actualizado el usuario exitosamente');
							$this->response->redirect("user/index");
						}
					}
					else{
						$this->flashSession->error("Las contraseñas no coinciden por favor verifica la información");
						$this->response->redirect("user/index");
					}
				}
				else{
					$this->flashSession->error("La contraseña es muy corta");
					$this->response->redirect("user/index");
				}
 			}
			
			$this->view->NewUserForm = $form;
		}
	}
	
	public function deleteAction($id)
	{
		$r = $this->verifyAcl('user', 'delete', '');
		if ($r)
			return $r;
		
		$idAccount = $this->user->account->idAccount;
		
		$user = User::findFirst("idUser = $id AND idAccount = $idAccount");
		
		if($user){
			if(!$user->delete()){
				foreach ($user->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
			}
			$this->flashSession->success("Usuario borrado exitosamente");
			$this->response->redirect("user/index");
		}
		else{
			$this->flash->error("El usuario que intenta borrar no existe, por favor verifique la información");
			$this->response->redirect("user/index");
		}
	}
}