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
				return $this->setJsonResponse(array('message' => 'Ha enviado campos vacíos, por favor valide la información'), 400, 'Datos invalidos');
			}
			
			if ($target === 'certain-accounts' && (empty($accounts) || count($accounts) <= 0)) {
				return $this->setJsonResponse(array('message' => 'Ha seleccionado determinadas cuentas, pero no ha seleccionado las cuentas, por favor valide la información'), 400, 'Datos invalidos');
			}
			
			$dest = new stdClass();
			$dest->type = $target;
			$dest->target = '';
			
			if ($target === 'certain-accounts') {
				$dest->target = $accounts;
			}
			
			try {
				$data = new stdClass();
				$data->name = $name;
				$data->rules = $rules;
				$data->target = json_encode($dest);
				$data->status = $status;
				
				$smart = new SmartManagmentWrapper();
				$smart->setData($data);
				$smart->saveSmart();
				$s = $smart->getSmart();
				
				return $this->setJsonResponse(array('message' => $s->idSmartmanagment), 200, 'Operación exitosa');
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('message' => 'Ha ocurrido un error, por favor contacte al administrador'), 500, 'Ocurrió un error');
			}
		}
		
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
	}
	
	public function editAction($idSmart)
	{
		$smart = Smartmanagment::findFirst(array(
			'conditions' => 'idSmartmanagment = ?1',
			'bind' => array(1 => $idSmart)
		));
		
		if (!$smart) {
			return $this->response->redirect("error");
		}
		
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$name = trim($name);
			$rules = $this->request->getPost('rules');
			$target = $this->request->getPost('target');
			$accounts = $this->request->getPost('accounts');
			$status = $this->request->getPost('status');
			
			if (empty($name) || empty($rules) || empty($target) || empty($status)) {
				return $this->setJsonResponse(array('error' => 'Ha enviado campos vacíos, por favor valide la información'), 400, 'Ha enviado campos vacios, por favor valide la informacion');
			}
			
			if ($target === 'certain-accounts' && (empty($accounts) || count($accounts) <= 0)) {
				return $this->setJsonResponse(array('error' => 'Ha seleccionado determinadas cuentas, pero no ha seleccionado las cuentas, por favor valide la información'), 400, 'Ha seleccionado determinadas cuentas, pero no ha seleccionado las cuentas, por favor valide la informacion');
			}
			
			$dest = new stdClass();
			$dest->type = $target;
			$dest->target = '';
			
			if ($target === 'certain-accounts') {
				$dest->target = $accounts;
			}
			
			try {
				$data = new stdClass();
				$data->name = $name;
				$data->rules = $rules;
				$data->target = json_encode($dest);
				$data->status = $status;
				
				$this->logger->log($data->target);
				
				$smartw = new SmartManagmentWrapper();
				$smartw->setData($data);
				$smartw->setSmart($smart);
				$smartw->editSmart();
				
				return $this->setJsonResponse(array('message' => "Se ha editado le gestión inteligente exitosamente"), 200, 'Operación exitosa');
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('message' => 'Ha ocurrido un error, por favor contacte al administrador'), 500, 'Ocurrió un error');
			}
		}
		
		$rules = Rule::findByIdSmartmanagment($smart->idSmartmanagment);
		
		$robj = array();
		if (count($rules) > 0) {
			foreach ($rules as $rule) {
				$robj[] = $rule->rule;
			}
		}
		
		$accountsSelected = json_decode($smart->target);		
		$accounts = Account::find();
		
		$this->view->setVar('accounts', $accounts);
		$this->view->setVar('accountsSelected', $accountsSelected);
		$this->view->setVar('smart', $smart);
		$this->view->setVar('rules', $robj);
	}
	
	
	public function contentAction($idSmart) 
	{
		$smart = Smartmanagment::findFirst(array(
			'conditions' => 'idSmartmanagment = ?1',
			'bind' => array(1 => $idSmart)
		));
		
		if (!$smart) {
			return $this->response->redirect("error");
		}
		
		if ($this->request->isPost()) {
			$editor = $this->request->getPost('editor');
			
			if (empty($editor)) {
				return $this->setJsonResponse(array('message' => 'Ha enviado campos vacíos, por favor valide la información'), 400, 'Datos invalidos');
			}
			
			try {
				$smart->content = $editor;
				
				if (!$smart->save()) {
					foreach ($smart->getMessages() as $msg) {
						$this->logger->log("Error while saving smart... {$msg}");
						throw new Exception("Exception... {$msg}");
					}
				}
				
				return $this->setJsonResponse(array('message' => "Se ha guardado el contenido exitosamente"), 200, 'Operación exitosa');
			}
			catch (Exception $ex) {
				$this->logger->log("Exception: {$ex}");
				return $this->setJsonResponse(array('message' => 'Ha ocurrido un error, por favor contacte al administrador'), 500, 'Ocurrió un error');
			}
		}
		
		$this->view->setVar('smart', $smart);
	}			
}
