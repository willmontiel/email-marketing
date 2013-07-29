<?php
class SessionController extends \Phalcon\Mvc\Controller
{
    
    public function indexAction()
    {
        
    }
    

    public function loginAction()
    {
        if ($this->request->isPost()) 
        {
            
         $user = $this->request->getPost("username");
         $pass = $this->request->getPost("pass");
         
         $valido = User::findFirst("username='$user' AND password='$pass' OR email='$user' AND password='$pass'");
         
         if ($valido != false) 
             {
                
                $u=$valido->user;
                $this->session->set("user-name", $u);
                
                $this->dispatcher->forward(array(
                "controller" => "index",
                "action" => "index"
                ));
            }
            
          else 
             {
                $this->flash->error("Password o Usuario Incorrecto, Por favor intenta de nuevo");
		$this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'index'
		));
	        return false;
            }
      
         
         
        }
        
    }
    
    
}