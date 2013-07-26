<?php
class AccountController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
	{
		$account= Account::find();
		$this->view->setVar("allAccount", $account);
//		$this->view->setVas("allUser", User::find());
	}

	public function newAction()
    {
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
				$user->type=1;
			    $user->account = $account;
				
				if($pass == $pass2){
				    $user->password = $this->security2->hash($pass);
					if (!$user->save()) {
						$this->db->rollback();
						foreach ($user->getMessages() as $msg) {
						$this->flash->error($msg);
						}
					}
					else {
					$this->db->commit();
					$this->flash->success('Se ha creado la cuenta exitosamente');					
					}
			   }
			   else{
				   $this->flash->error('Las contraseÃ±as no coincide por favor verifica la informaciÃ³n');
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
	    $users = User::find("idAccount = $id");
		
		$this->view->setVar("allUser", $users);	   
   }
 
     
   public function editAction($id)
   {
	    
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

//				return $this->response->redirect('account');
			}
       }

     }


//	public function deleteAction($id)
//	{
//		$account = Account::findFirstByIdAccount($id);
//		$user = User::findFirstByIdAccount($id);
//        
//		if ($account !== null) {
//			if ($this->request->isPost() && ($this->request->getPost('delete')=="DELETE")) {
//				$this->db->begin();
//			
//				if (!$user->delete()) {
//						$this->db->rollback();
//						foreach ($user->getMessages() as $msg) {
//						$this->flash->error($msg);
//						}
//						
//						foreach ($account->getMessages() as $msg) {
//						$this->flash->error($msg);
//						}
//					}
//					else {
//					$account->delete();
//					$this->db->commit();
//					$this->flashSession->success('Base de Datos Eliminada!');
//				    $this->response->redirect("account");					
//					}
//
//			} 
//			else {
//			$this->flash->error('Escriba la palabra "DELETE" correctamente');
//
////				$this->view->disable();
//
////				return $this->response->redirect('account');
//
//			}
//		}
		

//	}


 }  
