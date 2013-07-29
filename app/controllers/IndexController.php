<?php

class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $nom="prueba1";
        $this->session->set("user-name", $nom);
		
    }
}