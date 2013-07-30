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
			if ($this->security->checkToken()) {
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
				 else {
					if ($this->security2->checkHash($password, $user->password)) {
						$this->session->set('userid', $user->idUser);
						$this->session->set('authenticated', true);

						$this->response->redirect("");
					}

				 }
      
			}
         
        }
        
    }
    
    
}