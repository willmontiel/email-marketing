<?php

class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $nom="otra";
        $this->session->set("user-name", $nom); 
    }
}