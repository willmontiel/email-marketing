<?php

class CampaignController extends ControllerBase
{
	public function indexAction()
	{
		
	}
	
	public function listAction()
	{
		$autosends = Autoresponder::find(array(
				"conditions" => "idAccount = ?1",
				"bind" => array(1 => $this->user->idAccount),
				"order" => "createdon DESC"
			));
		
		$autoresponse = array();
		foreach ($autosends as $autosend) {
			$obj = new stdClass();
			$obj->idAutoresponder = $autosend->idAutoresponder;
			$obj->name = $autosend->name;
			$obj->category = $autosend->category;
			$obj->contentsource = $autosend->contentsource;
			$obj->active = $autosend->active;
			$obj->target = json_decode($autosend->target);
			$obj->subject = $autosend->subject;
			$obj->content = json_decode($autosend->content);
			$obj->previewData = $autosend->previewData;
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

//	public function newAction($type)
//	{
//		$account = $this->user->account;
//		
//		$dbases = Dbase::findByIdAccount($account->idAccount);
//		$this->view->setVar('dbases', $dbases);
//		
//		if ($this->request->isPost()) {
//			$campaignWrapper = new CampaignWrapper();
//			try {
//				$idDbase;
//			
//				$campaignWrapper->setDbase($dbase);
//			}
//			catch (Exception $e) {
//				$this->flashSession->error("Error: {$campaignWrapper->getFieldErrors[0]}");
//				$this->logger->log("Exception: {$e}");
//			}
//		}	
//	}
//	
//	public function editAction()
//	{
//		
//	}
//	
	public function deleteAction($idAutoresponder)
	{
		$autosend = Autoresponder::findFirst(array(
				"conditions" => "idAutoresponder = ?1",
				"bind" => array(1 => $idAutoresponder)
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
	
	public function automaticAction($idAutoresponder)
	{
		 if ($this->request->isPost()) {
			 
			try{ 
				$content = $this->request->getPost();
				$image = $this->cache->get($this->user->idUser . '-previewcampaign');
				$wrapper = new CampaignWrapper();
				$wrapper->setAccount($this->user->account);
				$wrapper->setPreviewImage($image);
				
				if($idAutoresponder) {
					$autoresponder = Autoresponder::findFirst(array(
							"conditions" => "idAutoresponder = ?1",
							"bind" => array(1 => $idAutoresponder)
						));
					$wrapper->setAutoresponder($autoresponder);
					$wrapper->updateAutomaticSend($content);
				}
				else {
					$wrapper->createAutoresponder($content, 'automatic', 'url');
				}
				return $this->response->redirect("campaign/list");
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				$this->flashSession->error($e->getMessage());
			}
		 }
		 
		 if($idAutoresponder){
			$autoresponder = Autoresponder::findFirst(array(
					"conditions" => "idAutoresponder = ?1",
					"bind" => array(1 => $idAutoresponder)
				));
			$autoresponder->time = json_decode($autoresponder->time);
			$autoresponder->days = json_decode($autoresponder->days);
			$autoresponder->from = json_decode($autoresponder->from);
			$autoresponder->target = json_decode($autoresponder->target);
			$autoresponder->content = json_decode($autoresponder->content);
			
			$this->view->setVar("autoresponse", $autoresponder);
		 }
		 
		$this->view->setVar('senders', Sender::findByIdAccount($this->user->idAccount));
		$this->view->setVar("contactlist", Contactlist::findContactListsInAccount($this->user->account));
		$this->view->setVar("dbases", Dbase::findByIdAccount($this->user->idAccount));
		$this->view->setVar("segments", Segment::findSegmentsInAccount($this->user->account));
	}
	
	public function changestatusAction($id)
	{
		if ($this->request->isPost() && $id) {
			$status = $this->request->getPost("state");

			$wrapper = new CampaignWrapper();
			$wrapper->setAccount($this->user->account);

			$autosend = Autoresponder::findFirst(array(
					"conditions" => "idAutoresponder = ?1",
					"bind" => array(1 => $id)
				));
			$wrapper->setAutoresponder($autosend);
			$wrapper->updateAutomaticSendStatus($status);
			
			return $this->setJsonResponse(array('status' => 'El estado de la autorespuesta se ha actualizado'), 201, 'Success');
		}
		
		return $this->setJsonResponse(array('status' => 'Error, el estado de la autorespuesta no se ha podido actualizar'),400, 'Error');
	}

	public function previewAction($id)
	{
		if ($this->request->isPost()) {
			try {
				$url = $this->request->getPost("url");
				if(!filter_var($url, FILTER_VALIDATE_URL)) {
					return $this->setJsonResponse(array('status' => 'Error, la url ingresada no es válida, por favor verifique la información'),400, 'Error');
				}
				$getHtml = new LoadHtml();
				$html = $getHtml->gethtml($url, false, false, $this->user->account);
				
				$wrapper = new CampaignWrapper();
				$wrapper->setAccount($this->user->account);
				
				$htmlFinal = $wrapper->insertCanvasHeader($id, $html, $this->url);
				
				$this->session->set('preview-template', $htmlFinal);
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
	
	public function previewimageAction($id)
	{
		try {
			$content = $this->request->getPost("img");
			
			if($id) {
				$wrapper = new CampaignWrapper();
				$wrapper->setAccount($this->user->account);

				$autosend = Autoresponder::findFirst(array(
						"conditions" => "idAutoresponder = ?1",
						"bind" => array(1 => $id)
					));
				$wrapper->setAutoresponder($autosend);
				$wrapper->updateCampaignPreviewImage($content);
			}
			else {
				$this->cache->save($this->user->idUser . '-previewcampaign', $content);
			}
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log("InvalidArgumentException {$e}");
		}
		catch (Exception $e){
			$this->logger->log("Exception {$e}");
		}
		
	}
}