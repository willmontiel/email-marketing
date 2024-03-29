<?php

class SmartmanagmentController extends ControllerBase
{
	public function indexAction()
	{
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$builder = $this->modelsManager->createBuilder()
			->from('Smartmanagment')
			->orderBy('idSmartmanagment');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder" => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page" => $currentPage
		));
		
		$page = $paginator->getPaginate();
		
		$this->view->setVar("page", $page);
	}
	
	public function newAction()
	{
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$name = trim($name);
			$description = $this->request->getPost('description');
			$description = trim($description);
			$rules = $this->request->getPost('rules');
			$datetime = $this->request->getPost('datetime');
			$target = $this->request->getPost('target');
			$accounts = $this->request->getPost('accounts');
			$status = $this->request->getPost('status');
			
			if (empty($name) || empty($description) || empty($rules) || empty($target) || empty($status) || empty($datetime)) {
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
				$data->description = $description;
				$data->rules = $rules;
				$data->datetime = $datetime;
				$data->target = json_encode($dest);
				$data->status = $status;
				
//				$this->logger->log(print_r($data, true));
				
				$smart = new SmartManagmentWrapper();
				$smart->setAccount($this->user->account);
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
			$description = $this->request->getPost('description');
			$description = trim($description);
			$rules = $this->request->getPost('rules');
			$datetime = $this->request->getPost('datetime');
			$target = $this->request->getPost('target');
			$accounts = $this->request->getPost('accounts');
			$status = $this->request->getPost('status');
			
			if (empty($name) || empty($description) || empty($rules) || empty($target) || empty($status)) {
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
				$data->description = $description;
				$data->datetime = $datetime;
				$data->rules = $rules;
				$data->target = json_encode($dest);
				$data->status = $status;
				
//				$this->logger->log(print_r($data, true));
				
				$smartw = new SmartManagmentWrapper();
				$smartw->setData($data);
				$smartw->setSmart($smart);
				$smartw->editSmart();
				
				$this->flashSession->notice("Se ha editado le gestión inteligente exitosamente");
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
			$subject = $this->request->getPost('subject');
			$fromName = $this->request->getPost('fromName');
			$fromEmail = $this->request->getPost('fromEmail');
			$replyTo = $this->request->getPost('replyTo');
			$editor = $this->request->getPost('editor');
			
			$this->logger->log("S: {$subject}");
			$this->logger->log("fn: {$fromName}");
			$this->logger->log("fe: {$fromEmail}");
			$this->logger->log("r: {$replyTo}");
			
			
			if (empty($subject) || empty($fromName) || empty($fromEmail) || empty($editor)) {
				return $this->setJsonResponse(array('message' => 'Ha enviado campos vacíos, por favor valide la información'), 400, 'Ha enviado campos vacios, por favor valide la informacion');
			}
			
			if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
				return $this->setJsonResponse(array('message' => 'La direccion de correo de remitente no es valida'), 400, 'La dirección de correo de remitente no es valida');
			}
			
			if (!empty($replyTo) && !filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
				return $this->setJsonResponse(array('message' => 'El campo "Responder a", no contiene una dirección de correo valida'), 400, 'El campo "Responder a", no contiene una direccion de correo valida');
			}
			
			try {
				$smart->subject = $subject;
				$smart->fromName = $fromName;
				$smart->fromEmail = $fromEmail;
				if (!empty($replyTo)) {
					$smart->replyTo = $replyTo;
				}
				$smart->content = $editor;
				
				if (!$smart->save()) {
					foreach ($smart->getMessages() as $msg) {
						$this->logger->log("Error while saving smart... {$msg}");
						throw new Exception("Exception... {$msg}");
					}
				}
				
				return $this->setJsonResponse(array('message' => "Se ha guardado el contenido exitosamente"), 200, 'Se ha guardado el contenido exitosamente');
			}
			catch (Exception $ex) {
				$this->logger->log("Exception: {$ex}");
				return $this->setJsonResponse(array('message' => 'Ha ocurrido un error, por favor contacte al administrador'), 500, 'Ha ocurrido un error, por favor contacte al administrador');
			}
		}
		
		$this->view->setVar('smart', $smart);
	}
	
	public function previewcontentAction($idSmart)
	{
		$content = $this->request->getPost("editor");
		$this->session->remove('smart-preview');
		$url = $this->url->get('smartmanagment/createpreview');		
		$editorObj = new HtmlObj(true, $url, $idSmart);
		$editorObj->assignContent(json_decode($content));
		$this->session->set('smart-preview', $editorObj->render());
		
		return $this->setJsonResponse(array('status' => 'Success'), 201, 'Success');
	}
	
	public function previewdataAction()
	{
		$htmlObj = $this->session->get('smart-preview');
		$this->session->remove('smart-preview');

		$this->view->disable();
		return $this->response->setContent($htmlObj);
	}
	
	public function createpreviewAction($idSmart)
	{
		$content = $this->request->getPost("img");
		$imgObj = new ImageObject();
		$imgObj->createFromBase64($content);
		$imgObj->resizeImage(200, 250);
		$newImg = $imgObj->getImageBase64();
		
		$this->logger->log("Preview: {$newImg}");
		
		$smart = Smartmanagment::findFirst(array(
			'conditions' => 'idSmartmanagment = ?1',
			'bind' => array(1 => $idSmart)
		));
		
		$smart->preview = $newImg;
		
		if (!$smart->save()) {
			foreach ($smart->getMessages() as $msg) {
				$this->logger->log("Error while saving image base64: " . $msg);
			}
		}
	}
	
	public function deleteAction($id)
	{
		$smart = Smartmanagment::findFirst(array(
			'conditions' => 'idSmartmanagment = ?1',
			'bind' => array(1 => $id)
		));
		
		if (!$smart) {
			$this->flashSession->error("La gestión inteligente que desea eliminar no se encuentra, por favor valide la información");
			return $this->response->redirect('smartmanagment');
		}
		
		try {
			$rules = Rule::findByIdSmartmanagment($smart->idSmartmanagment);
			
			if (count($rules) > 0) {
				foreach ($rules as $rule) {
					if (!$rule->delete()) {
						foreach ($rule->getMessages() as $msg) {
							$this->logger->log("Error while deleting rule {$rule->idRule}... {$msg}");
						}
					}
				}
			}
			
			if (!$smart->delete()) {
				foreach ($smart->getMessages() as $msg) {
					$this->logger->log("Error while deleting smart... {$msg}");
					$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
					return $this->response->redirect('smartmanagment');
				}
			}
			
			$this->flashSession->warning("Se ha eliminado la gestión inteligente exitosamente");
			return $this->response->redirect('smartmanagment');
		}
		catch (Exception $e) {
			$this->logger->log("Exception... {$e}");
			$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
			return $this->response->redirect('smartmanagment');
		}
	}
	
	public function previewAction($id)
	{
		$smart = Smartmanagment::findFirst(array(
			'conditions' => 'idSmartmanagment = ?1',
			'bind' => array(1 => $id)
		));
		
		if (!$smart) {
			return $this->setJsonResponse(array('status' => 'error'), 401, 'Error');
		}
		
		$editorObj = new HtmlObj();
//		$editorObj->setAccount($account);
		$editorObj->assignContent(json_decode($smart->content));
		$response = $editorObj->render();
		
		return $this->setJsonResponse(array('preview' => $response));
	}
	
	public function behaviorAction()
	{
		$account = $this->user->account;
		
		$currentPage = $this->request->getQuery('page', null, 1); // GET

		$builder = $this->modelsManager->createBuilder()
			->from('Scorehistory')
			->leftJoin('Smartmanagment', 'Scorehistory.idSmartmanagment = Smartmanagment.idSmartmanagment')
			->leftJoin('Mail', 'Scorehistory.idMail = Mail.idMail')	
			->where("Scorehistory.idAccount = {$account->idAccount}")
			->orderBy('Scorehistory.idScorehistory DESC	');

		$paginator = new Phalcon\Paginator\Adapter\QueryBuilder(array(
			"builder" => $builder,
			"limit"=> PaginationDecorator::DEFAULT_LIMIT,
			"page" => $currentPage
		));
		
		$page = $paginator->getPaginate();
		$score = Score::findFirstByIdAccount($account->idAccount);
		
		$array1 = array();
		$array2 = array();
		$i = 0;
		
		$history = Scorehistory::find(array(
			'conditions' => 'idAccount = ?1',
			'bind' => array(1 => $account->idAccount)
		));
		
		foreach ($history as $value) {
			if ($i == 0) {
				$array1[] = $value->score;
				$array2[$value->idScorehistory] = $value->score;
			}
			else {
				$array1[] = $value->score + $array1[$i - 1];
				$array2[$value->idScorehistory] = $value->score + $array1[$i - 1];
			}
			$i++;
		}
		
		unset($array1);
		
		$this->view->setVar("page", $page);
		$this->view->setVar("account", $account);
		$this->view->setVar("score", $score);
		$this->view->setVar("accumulated", $array2);
	}
}
