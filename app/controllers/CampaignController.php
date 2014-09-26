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
			$obj->target = $this->getTargetFromMail($autosend);
			
			$subject = json_decode($autosend->subject);
			$obj->subject = $subject->text;
			
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
			$autoresponder->target = $autoresponder->target;
			$autoresponder->content = json_decode($autoresponder->content);
			$autoresponder->subject = json_decode($autoresponder->subject);
			
			$this->view->setVar("autoresponse", $autoresponder);
		 }
		 
		$this->view->setVar('senders', Sender::findByIdAccount($this->user->idAccount));
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
	
	private function getTargetFromMail($mail)
	{
		$t = json_decode($mail->target);
		$target = "Indefinida";
		$ids = explode(',', $t->ids);
		
		switch ($t->destination) {
			case 'contactlists':
				$target = "Listas de contactos: ";
				foreach ($ids as $id) {
					$list = Contactlist::findFirst(array(
						'conditions' => "idContactlist = ?1",
						'bind' => array(1 => $id)
					));
					if ($list) {
						$target .= "{$list->name}, ";
					}
				}
				break;
			case 'dbases':
				$target = "Bases de datos: ";
				foreach ($t->ids as $id) {
					$list = Dbase::findFirst(array(
						'conditions' => "idDbase = ?1",
						'bind' => array(1 => $id)
					));
					if ($list) {
						$target .= "{$list->name}, ";
					}
				}
				break;
			case 'segments':
				$target = "Segmentos: ";
				foreach ($t->ids as $id) {
					$list = Segment::findFirst(array(
						'conditions' => "idSegment = ?1",
						'bind' => array(1 => $id)
					));

					if ($list) {
						$target .= "{$list->name}, ";
					}
				}
				break;
			default:
				break;
		}
		return $target;
	}
}