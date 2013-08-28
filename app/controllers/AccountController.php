<?php
class AccountController extends ControllerBase
{
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
//		$this->view->setVar("allAccount", $account);
		
	}
	
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
						else {
							$dbase = new Dbase();
							$dbase->account = $account;
							$dbase->name = "Mi base de datos";
							
							if($dbase->save()) {
								$contactList = new Contactlist();
								$contactList->dbase = $dbase;
								$contactList->name = "Mi lista de contactos";
								$contactList->Cdescription = "Sin descripción de contactos";
								
								if (!$contactList->save()) {
									$this->db->rollback();
									foreach ($contactList->getMessages() as $msg) {
										$this->flash->error($msg);
									}
								}
								else {
									$this->db->commit();
									$this->flashSession->success('Se ha creado la cuenta exitosamente');
									$this->response->redirect("account");
								}
							}
							else {
								$this->db->rollback();
								foreach ($dbase->getMessages() as $msg) {
									$this->flash->error($msg);
								}
							
							}
						}
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
   
   public function showAction($id)
   {
	   $r = $this->verifyAcl('account', 'show', '');
		if ($r)
			return $r;
	    $users = User::find("idAccount = $id");
		
		$this->view->setVar("allUser", $users);	   
   }
 
     
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
					$this->flash->success('Se ha creado la cuenta exitosamente');
					$this->response->redirect("account");
					}

 			}
		$this->view->editFormAccount = $editform;

        } 
    }

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

   //				$this->view->disable();
			
			   }
       }

     }

 }  
