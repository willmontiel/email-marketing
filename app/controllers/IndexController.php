<?php

class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
<<<<<<< HEAD
        
        $name=$this->session->get("user-name", $u); 
=======
        $nom="prueba1";
        $this->session->set("user-name", $nom);
		
>>>>>>> 9753e5c9141cfbcbe576a145c4d8a2384de6b64e
    }
}