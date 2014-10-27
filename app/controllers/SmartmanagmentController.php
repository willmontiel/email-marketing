<?php

class SmartmanagmentController extends ControllerBase
{
	public function indexAction()
	{
		
	}
	
	public function newAction()
	{
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
	}
}
