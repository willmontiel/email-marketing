<?php
class SessionController extends \Phalcon\Mvc\Controller
{
    
    public function signinAction()
    {
        
    }
    

	public function logoutAction()
    {
        $this->session->remove("user-name");
        $this->session->destroy();
		
        $this->response->redirect("session/signin");
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
			
			
			 if (!$user) {
				 
				$this->flash->error("Password o Usuario Incorrecto, Por favor intenta de nuevo");
				return false;
			 }
            
			 else 
             {
//                
//				$this->response->redirect("session/signin");
				if ($this->security2->checkHash($password, $user->password)) {
                //The password is valid
                $this->session->set("user-name", $login);
                
                $this->response->redirect("");
				}
				
			 }
      
         
         
        }
        
    }
    
    
}