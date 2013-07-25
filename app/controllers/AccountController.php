<?php
class AccountController extends \Phalcon\Mvc\Controller
{
    
    public function newAction()
    {
        $account = new Account();
        $form = new AccountForm($account);
      
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
				   $this->flash->error('Las contraseñas no coincide por favor verifica la información');
			   }
			}
            else {
				foreach ($account->getMessages() as $msg) {
                $this->flash->error($msg);
                }
            }
        }
      
        
      $this->view->form = $form;
 
     
   }     
     
 }  
