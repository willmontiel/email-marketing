<?php

class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        
        $name=$this->session->get("user-name", $u); 
    }
}