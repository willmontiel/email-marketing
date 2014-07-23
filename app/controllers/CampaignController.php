<?php

class CampaignController extends ControllerBase
{
	public function indexAction()
	{
		
	}
	
	public function listAction()
	{
		$autosends = Autosend::find(array(
				"conditions" => "idAccount = ?1",
				"bind" => array(1 => $this->user->idAccount),
				"order" => "createdon DESC"
			));
		
		$autoresponse = array();
		foreach ($autosends as $autosend) {
			$obj = new stdClass();
			$obj->id = $autosend->idAutosend;
			$obj->name = $autosend->name;
			$obj->category = $autosend->category;
			$obj->type = $autosend->type;
			$obj->activated = $autosend->activated;
			$obj->target = $autosend->target;
			$obj->subject = $autosend->subject;
			$obj->content = $autosend->content;
			$obj->time = json_decode($autosend->time);
			$obj->days = $this->renameDays( json_decode($autosend->days) );
			$autoresponse[] = $obj;
		}
		
		$this->view->setVar("autoresponse", $autoresponse);
	}
	
	protected function renameDays($days)
	{
		$new_days = array();
		foreach ($days as $d) {
			switch ($d) {
				case "monday":
					$new_days[]= "Lunes";
					break;
				case "tuesday":
					$new_days[]= "Martes";
					break;
				case "wednesday":
					$new_days[]= "Miércoles";
					break;
				case "thursday":
					$new_days[]= "Jueves";
					break;
				case "friday":
					$new_days[]= "Viernes";
					break;
				case "saturday":
					$new_days[]= "Sábado";
					break;
				case "sunday":
					$new_days[]= "Domingo";
					break;
			}
		}
		
		return $new_days;
	}

	public function newAction($type)
	{
		$account = $this->user->account;
		
		$dbases = Dbase::findByIdAccount($account->idAccount);
		$this->view->setVar('dbases', $dbases);
		
		if ($this->request->isPost()) {
			$campaignWrapper = new CampaignWrapper();
			try {
				$idDbase;
			
				$campaignWrapper->setDbase($dbase);
			}
			catch (Exception $e) {
				$this->flashSession->error("Error: {$campaignWrapper->getFieldErrors[0]}");
				$this->logger->log("Exception: {$e}");
			}
		}	
	}
	
	public function editAction()
	{
		
	}
	
	public function deleteAction($idAutosend)
	{
		$autosend = Autosend::findFirst(array(
				"conditions" => "idAutosend = ?1",
				"bind" => array(1 => $idAutosend)
			));
		
		if($autosend->idAccount === $this->user->idAccount) {
			if (!$autosend->delete()) {
				foreach ($autosend->getMessages() as $msg) {
					$this->logger->log('Error: ' . $msg);
					$this->flashSession->error('No se pudo eliminar el envío automático');
				}
			}
			else {
				$this->flashSession->warning('El envío automático se eliminó exitosamente');
			}
		}
		else {
			$this->flashSession->error('No se pudo eliminar el envío automático');
		}
		
		return $this->response->redirect("campaign/list");
	}
	
	public function automaticAction($idAutosend)
	{
		 if ($this->request->isPost()) {
			 
			try{ 
				$content = $this->request->getPost();
				$wrapper = new CampaignWrapper();
				$wrapper->setAccount($this->user->account);

				if($idAutosend) {
					$autosend = Autosend::findFirst(array(
							"conditions" => "idAutosend = ?1",
							"bind" => array(1 => $idAutosend)
						));
					$wrapper->setAutosend($autosend);
					$wrapper->updateAutomaticSend($content);
				}
				else {
					$wrapper->createAutosend($content, 'automatic', 'url');
				}
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
			}
			
			return $this->response->redirect("campaign/list");
		 }
		 else if($idAutosend){
			$autosend = Autosend::findFirst(array(
					"conditions" => "idAutosend = ?1",
					"bind" => array(1 => $idAutosend)
				));
			$autosend->time = json_decode($autosend->time);
			$autosend->days = json_decode($autosend->days);
			
			$this->view->setVar("autoresponse", $autosend);
		 }
	}
	
	public function previewAction()
	{
		if ($this->request->isPost()) {
			try {
				$url = $this->request->getPost("url");
				if(!filter_var($url, FILTER_VALIDATE_URL)) {
					return $this->setJsonResponse(array('status' => 'Error, la url ingresada no es válida, por favor verifique la información'),400, 'Error');
				}
				$getHtml = new LoadHtml();
				$html = $getHtml->gethtml($url, false, false, $this->user->account);
				$this->session->set('preview-template', $html);
				return $this->setJsonResponse(array('status' => 'Success'), 201, 'Success');
			}
			catch (\InvalidArgumentException $e) {
				$this->logger->log("InvalidArgumentException {$e}");
			}
			catch (Exception $e){
				$this->logger->log("Exception {$e}");
			}

			return $this->setJsonResponse(array('status' => 'Error'), 400, 'Error');
		 }
	}
	
	public function previewframeAction()
	{
		$html = $this->session->get('preview-template');
		$this->session->remove('preview-template');
		$this->view->disable();
		
		return $this->response->setContent($html);
	}
}