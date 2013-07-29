<?php
class SessionController extends \Phalcon\Mvc\Controller
{
    
    public function signinAction()
    {
        
    }
    

    public function loginAction()
    {
		if ($this->request->isPost()) 
        {
			$login = $this->request->getPost("username");
			$password = $this->request->getPost("pass");
		 
			$user = User::findFirst(array(
				"username = ?0",
				"bind" => array($login)
			));
			
			
			 if ($user) {
				if ($this->security2->checkHash($password, $user->password)) {
                //The password is valid
                $this->session->set("user-name", $login);
                
                $this->dispatcher->forward(array(
                "controller" => "dbase",
                "action" => "index"
                ));
				}
			}
            
			else 
            {
                $this->flash->error("Password o Usuario Incorrecto, Por favor intenta de nuevo");
				echo $pass2;
				$this->dispatcher->forward(array(
					'controller' => 'session',
					'action' => 'index'
				));
				return false;
			}
      
         
         
        }
        
    }
    
    
}