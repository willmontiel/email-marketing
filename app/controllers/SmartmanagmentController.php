<?php

class SmartmanagmentController extends ControllerBase
{
	public function indexAction()
	{
		
	}
	
	public function newAction()
	{
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$name = trim($name);
			$rules = $this->request->getPost('rules');
			$target = $this->request->getPost('target');
			$accounts = $this->request->getPost('accounts');
			$status = $this->request->getPost('status');
			
			if (empty($name) || empty($rules) || empty($target) || empty($status)) {
				return $this->setJsonResponse(array('error' => 'Ha enviado campos vacíos, por favor valide la información'), 400, 'Datos inválidos');
			}
		}
		
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
	}
}
