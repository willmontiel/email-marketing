<?php
class AccountController extends ControllerBase
{
	/**
	 * 
	 * Esta funcion se encarga de retornar en json la cantidad de contactos activos por cuenta, para
	 * visualizarlos desde la sección de listas de contactos, esto se hace con jquery que hará la peticion
	 * cada determinado tiempo para mentener actualizada la información al usuario.
	 */
	public function loadcontactsinfoAction()
	{
		$account = $this->user->account;
		$currentActiveContacts = $this->user->account->countActiveContactsInAccount();
		
		// Convirtiendo a Json
		$object = array();
		$object['activeContacts'] = $currentActiveContacts;
		$object['contactLimit'] = $account->contactLimit;
		$object['accountingMode'] = $account->accountingMode;
					
		return $this->setJsonResponse($object);
	
	}
	
	/**
	 * 
	 * Esta función se encarga de mostrar toda la información de las cuentas al super-administrador
	 */
	public function indexAction()
	{
		$r = $this->verifyAcl('account', 'list', '');
		if ($r)
			return $r;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$builder = $this->modelsManager->createBuilder()
			->from('Account')
			->orderBy('idAccount');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder" => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page" => $currentPage
		));
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
		
	}
	
	/*
	 * Esta función se utiliza para crear cuentas con su respectivo usuario y base de datos base, esta funcion es
	 * utilizada solo por los super-administradores  
	 */
	public function newAction()
    {
		$r = $this->verifyAcl('account', 'new', '');
		if ($r)
			return $r;
        $account = new Account();
        $form = new NewAccountForm($account);
      
        if ($this->request->isPost()) {
            
            $form->bind($this->request->getPost(), $account);
			$this->db->begin();
            if ($form->isValid() && $account->save()) {
            
				$user = new User();
				
				$user->email = $form->getValue('email');
				$user->firstName = $form->getValue('firstName');
				$user->lastName = $form->getValue('lastName');				
		$pass =	$user->password = $form->getValue('password');
		$pass2=	$user->password2 = $form->getValue('password2');
				$user->username = $form->getValue('username');  
				$user->userrole='ROLE_ADMIN';
			    $user->account = $account;
				
				if($pass == $pass2){
					if(strlen($pass) >= 8){
						$user->password = $this->security2->hash($pass);
						if (!$user->save()) {
							$this->db->rollback();
							foreach ($user->getMessages() as $msg) {
							$this->flash->error($msg);
							}
						}
						
						$this->db->commit();
						$this->flashSession->success('Se ha creado la cuenta exitosamente');
						$this->response->redirect("account");
					}
							
					else{
						$this->flash->error("La contraseña es muy corta, debe estar entre 8 y 40 caracteres");
					}
				}
				else{
					   $this->flash->error('Las contraseñas no coinciden por favor verifica la información');
				}		
			}
            else {
				foreach ($account->getMessages() as $msg) {
                $this->flash->error($msg);
                }
            }
        }
       
		$this->view->newFormAccount = $form;
  
   } 
   
   /*
    * Esta función se encarga de mostrar todos los usuarios de todas la cuentas. Es utilizada por el super-administrador
    * Recibe el id de la cuenta
    */
   public function showAction($id)
   {
		$r = $this->verifyAcl('account', 'show', '');
		if ($r)
			return $r;	 

		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$paginator = new \Phalcon\Paginator\Adapter\Model(
			array(
				"data" => User::find(array(
					"conditions" => "idAccount = ?1",
					"bind" => array(1 => $id)
				)),
				"limit"=> PaginationDecorator::DEFAULT_LIMIT,
				"page" => $currentPage
			)
		);

		$page = $paginator->getPaginate();

		$this->view->setVar("page", $page);
		$this->view->setVar("idAccount", $id);
   }
 
   /*
    * Esta función se encarga de editar información de una cuenta. Es utilizada por el super-administador
    * Recibe el id de la cuenta
    */  
   public function editAction($id)
   {
	    $r = $this->verifyAcl('account', 'edit', '');
		if ($r)
			return $r;
	    $account = Account::findFirstByIdAccount($id);
		if ($account !== null) {
            $this->view->setVar("allAccount", $account);
			$editform = new EditAccountForm($account);

 			if ($this->request->isPost()) {   
					$editform->bind($this->request->getPost(), $account);
					$this->db->begin();
								
					if (!$editform->isValid() OR !$account->save()) {
						$this->db->rollback();
						foreach ($account->getMessages() as $msg) {
						$this->flash->error($msg);
						}
					}
					else {
						$this->db->commit();
						$this->flash->success('Se ha editado la cuenta exitosamente');
						$this->response->redirect("account");
					}

 			}
		$this->view->editFormAccount = $editform;

        } 
    }
	
	/*
	 * Esta función se encagar de borrar una cuenta de la base de datos, es muy delicada. Es utilizada por el administrador
	 * recibe el id de la cuenta
	 */
	public function deleteAction($id)
    {
		$r = $this->verifyAcl('account', 'delete', '');
		if ($r)
			return $r;
		
		$account = Account::findFirstByIdAccount($id);
		$user = User::findFirstByIdAccount($id);

			if ($account !== null) {
				if ($this->request->isPost() && ($this->request->getPost('delete')=="DELETE")) {
				   $account->delete();
				   $user->deleted();
				   $this->flashSession->success('Base de Datos Eliminada!');
				   $this->response->redirect("account");
				} 

			   else {
				   $this->flash->error('Escriba la palabra "DELETE" correctamente');
			   }
       }

     }
	 
	 /*
	  * Esta funcion se encarga de crear usuarios a partir del id de la cuenta
	  */
	public function newuserAction($id)
	{
		$r = $this->verifyAcl('account', 'newuser', '');
		if ($r)
			return $r;

		$user = new User();
		$form = new NewUserForm($user);
		
		$account = Account::findFirst(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $id)
		));
		
		if(!$account){
			$this->view->disable();
			$this->flashSession->error("La cuenta no existe");
			$this->response->redirect("account/index");
		}
		
		else {
			if ($this->request->isPost()) {
				$form->bind($this->request->getPost(), $user);

				$pass = $form->getValue('password');
				$pass2 = $form->getValue('password2');

				if(strlen($pass) < 8) {
					 $this->flashSession->error("La contraseña es muy corta, debe estar entre 8 y 40 caracteres");
					 $this->response->redirect("account/newuser");
				}

				else {
					if($pass !== $pass2) {
						$this->flashSession->error("Las contraseñas no coinciden por favor verifica la información");
						$this->response->redirect("account/newuser");
					}
					else {

						$this->db->begin();
						$user->idAccount = $id;
						$user->password = $this->security2->hash($pass);

						if ($form->isValid() && $user->save()) {
							$this->db->commit();
							$this->flashSession->success("Se ha creado el usuario exitosamente");
							$this->response->redirect("account/index");
						}

						else {
							$this->db->rollback();
							foreach ($user->getMessages() as $msg) {
								$this->flash->error($msg);
							}
						}
					}
				}
			 }

			 $this->view->NewUserForm = $form;
			 $this->view->setVar('account', $account);
			
		}
	}
	
	
	/*
	 * Esta función le permite al super-administrador editar cualquier usuario de cualquier cuenta
	 * Recibe el id del usuario
	 */
	public function edituserAction($id)
	{
		$r = $this->verifyAcl('account', 'edituser', '');
		if ($r)
			return $r;
		
		$userExist = User::findFirst(array(
			"conditions" => "idUser = ?1",
			"bind" => array(1 => $id)
		));
		
		if(!$userExist){
			$this->view->disable();
			$this->flashSession->error("el usuario no existe");
			$this->response->redirect("account/index");
		}
		else {
			if ($userExist !== null) {
				$this->view->setVar("user", $userExist);
				$form = new NewUserForm($userExist);

				if ($this->request->isPost()) {   
					
					$form->bind($this->request->getPost(), $userExist);
					$this->db->begin();

					if (!$form->isValid() OR !$userExist->save()) {
						$this->db->rollback();
						
						foreach ($userExist->getMessages() as $msg) {
							$this->flashSession->error($msg);
						}
					}
					else {
						$this->db->commit();
						$this->flashSession->success('Se ha editado el usuario ' .$userExist->username. ' de la cuenta ' .$userExist->idAccount. ' exitosamente');
						$this->response->redirect("account/index");
					}
				}
				$this->view->NewUserForm = $form;
			} 	
		}
	}
	
	/*
	 * Esta funcion permite al super-administrador eliminar cualquier usuario de cualquier cuenta
	 * recibe el id el usuario
	 */
	public function deleteuserAction($id)
	{
		$r = $this->verifyAcl('account', 'deleteuser', '');
		if ($r)
			return $r;
		
		$user = User::findFirst(array(
			"conditions" => "idUser = ?1",
			"bind" => array(1 => $id)
		));
		
		if(!$user){
			$this->view->disable();
			$this->flashSession->error('No existe el usuario');
			$this->response->redirect("account/index");
		}
		else {
			if(!$user->delete()) {
				foreach ($user->getMessages() as $msg) {
					$this->flashSession->error($msg);
				}
			}
			$this->flashSession->success('Se ha eliminado el usuario!');
			$this->response->redirect("account/show/".$user->idAccount);
		}
	}
 }  
