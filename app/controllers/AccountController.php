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
	    
	    //Recuperar la informacion de la BD que se desea SI existe
		$db = $this->findAndValidateDbaseAccount($id);
		if ($db !== null) {
            $this->view->setVar("edbase", $db);
			//Instanciar el formulario y Relacionarlo con los atributos del Model Dbases
			$editform = new EditForm($db);

			if ($this->request->isPost()) {   
				$editform->bind($this->request->getPost(), $db);

				if ($editform->isValid() && $db->save()) {
					$this->flash->success('Base de Datos Actualizada Exitosamente!');
					$this->dispatcher->forward(
						array(
							'controller' => 'dbases',
							'action' => 'show'
						)
					);
				}
				else {
					foreach ($db->getMessages() as $msg) {
						$this->flash->error($msg);
					}
				}
			}
			$this->view->editform = $editform;
        } 
   }
 }  
