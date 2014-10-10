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
			$obj->type = $autosend->type;
			$obj->contentsource = $autosend->contentsource;
			$obj->active = $autosend->active;
			
			$raw_target = json_decode($autosend->target);
			$target_wrapper = new \EmailMarketing\General\Misc\InterpreterTarget();
			$target_wrapper->setData($raw_target);
			$target_wrapper->createModel();
			
			$target_info = new stdClass();
			$target_info->criteria = $this->getCriteriaName($target_wrapper->getCriteria());
			$target_info->names = $target_wrapper->getNames();
			
			$obj->target = $target_info;
			
			$subject = json_decode($autosend->subject);
			$obj->subject = $subject->text;
			
			if($autosend->contentsource == 'url') {
				$url_obj= json_decode($autosend->content);
				$obj->content = $url_obj->url;
			}
			else {
				$obj->content = $autosend->content;
			}
			
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
				$type = $this->request->getPost("type");
				
				switch ($type) {
					case 'url':
						$url = $this->request->getPost("content");
						if(!filter_var($url, FILTER_VALIDATE_URL)) {
							return $this->setJsonResponse(array('status' => 'Error, la url ingresada no es válida, por favor verifique la información'),400, 'Error');
						}
						$getHtml = new LoadHtml();
						$html = $getHtml->gethtml($url, false, false, $this->user->account);
						break;
					case 'html':
						$html = html_entity_decode($this->request->getPost("content"));
						$footer = Footer::findFirstByIdFooter($this->user->account->idFooter);
						if($this->user->account->footerEditable == 0) {
							$html = str_replace("</body></html>", $footer->html . "</body></html>", $html);
						}
						if (trim($html) === '' || $html == null || empty($html)) {
							return $this->setJsonResponse(array('status' => 'Error'), 401, 'No hay html que previsualizar por favor verfique la informacion');
						}
						break;
					case 'editor':
						$content = $this->request->getPost("content");
						$editorObj = new HtmlObj();
						$editorObj->setAccount($this->user->account);
						$editorObj->assignContent(json_decode($content));
						$html = $editorObj->render();
						break;
				}
				
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
	
	public function previewlistAction($idAutoresponder)
	{
		try {
			if($idAutoresponder) {
				$autoresponder = Autoresponder::findFirst(array(
						"conditions" => "idAutoresponder = ?1",
						"bind" => array(1 => $idAutoresponder)
					));
				
				if($autoresponder) {
					switch ($autoresponder->contentsource) {
						case 'url':
							$url = json_decode($autoresponder->content);
							if(!filter_var($url->url, FILTER_VALIDATE_URL)) {
								return $this->setJsonResponse(array('status' => 'Error, la url ingresada no es válida, por favor verifique la información'),400, 'Error');
							}
							$getHtml = new LoadHtml();
							$html = $getHtml->gethtml($url->url, false, false, $this->user->account);
							break;
						case 'html':
							$html = html_entity_decode($autoresponder->content);
							$footer = Footer::findFirstByIdFooter($this->user->account->idFooter);
							if($this->user->account->footerEditable == 0) {
								$html = str_replace("</body></html>", $footer->html . "</body></html>", $html);
							}
							if (trim($html) === '' || $html == null || empty($html)) {
								return $this->setJsonResponse(array('status' => 'Error'), 401, 'No hay html que previsualizar por favor verfique la informacion');
							}
							break;
						case 'editor':
							$content = $autoresponder->content;
							$editorObj = new HtmlObj();
							$editorObj->setAccount($this->user->account);
							$editorObj->assignContent(json_decode($content));
							$html = $editorObj->render();
							break;
					}

					$wrapper = new CampaignWrapper();
					$wrapper->setAccount($this->user->account);

					$htmlFinal = $wrapper->insertCanvasHeader($idAutoresponder, $html, $this->url);

					$this->session->set('preview-template', $htmlFinal);
					return $this->setJsonResponse(array('status' => 'Success'), 201, 'Success');
				}
			}

		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log("InvalidArgumentException {$e}");
		}
		catch (Exception $e){
			$this->logger->log("Exception {$e}");
		}
		return $this->setJsonResponse(array('status' => 'Error'), 400, 'Error');
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
	
	private function getCriteriaName($criteria)
	{
		$name = "Indefinida";
		switch ($criteria) {
			case 'contactlists':
				$name = "Listas de contactos";
				break;
			case 'dbases':
				$name = "Bases de datos";
				break;
			case 'segments':
				$name = "Segmentos";
				break;
			default:
				break;
		}
		return $name;
	}
	
	public function birthdayAction($idAutoresponder, $option)
	{
		 if ($this->request->isPost()) {
			try {
				
				$content = $this->request->getPost();
				$content['monday'] = $content['tuesday'] = $content['wednesday'] = $content['thursday'] = $content['friday'] = $content['saturday'] = $content['sunday'] = 'on';
				$image = $this->cache->get($this->user->idUser . '-previewcampaign');
				
				$wrapper = new CampaignWrapper();
				$wrapper->setAccount($this->user->account);
				$wrapper->setPreviewImage($image);
				if($idAutoresponder != 'null' && !empty($idAutoresponder)) {
					$autoresponder = Autoresponder::findFirst(array(
							"conditions" => "idAutoresponder = ?1",
							"bind" => array(1 => $idAutoresponder)
						));
					if ($autoresponder) {
						$wrapper->setAutoresponder($autoresponder);
						$wrapper->updateAutomaticSend($content);
					}
				}
				else {
					$idAutoresponder = $wrapper->createAutoresponder($content, 'birthday', null);
				}
				if($option == 'onlyId') {
					return $this->setJsonResponse(array('status' => $idAutoresponder), 201, 'Success');
				}
				return $this->response->redirect("campaign/list");
			}
			catch (Exception $e) {
				$this->logger->log("Exception: {$e}");
				if($option == 'onlyId') {
					return $this->setJsonResponse(array('status' => $e->getMessage()), 500, 'Error');
				}
				$this->flashSession->error($e->getMessage());
			}
		 }
		 
		 if($idAutoresponder){
			$autoresponder = Autoresponder::findFirst(array(
					"conditions" => "idAutoresponder = ?1",
					"bind" => array(1 => $idAutoresponder)
				));
			$autoresponder->time = json_decode($autoresponder->time);
			$autoresponder->from = json_decode($autoresponder->from);
			$autoresponder->content = json_decode($autoresponder->content);
			$autoresponder->subject = json_decode($autoresponder->subject);
			
			$this->view->setVar("autoresponse", $autoresponder);
		 }
		 
		$this->view->setVar('senders', Sender::findByIdAccount($this->user->idAccount));
	}
	
	public function contenteditorAction($idAutoresponder)
	{
		 if ($this->request->isPost()) {
			try {
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
					$wrapper->createCampaignContent($content['editor'], 'editor');

					switch ($autoresponder->type) {
						case 'birthday' :
							return $this->setJsonResponse(array('status' => "birthday"), 201, 'Success');
							break;
					}
				}
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
			
			if (empty($autoresponder->content)) {
				$objContent = 'null';
			}
			else {
				$objContent = $autoresponder->content;
			}
			
			$this->view->setVar('objMail', $objContent);
			$this->view->setVar("autoresponder", $autoresponder);
		 }
	}
	
	public function contenthtmlAction($idAutoresponder)
	{
		 if ($this->request->isPost()) {
			try {
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
					$wrapper->createCampaignContent($content['content'], 'html');

					switch ($autoresponder->type) {
						case 'birthday' :
							return $this->setJsonResponse(array('status' => "birthday"), 201, 'Success');
							break;
					}
				}
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
			
			if (!empty($autoresponder->content)) {
				$content = html_entity_decode($autoresponder->content);
				$this->view->setVar("content", $content);
			}
			
			$this->view->setVar("autoresponder", $autoresponder);
		 }
	}
	
	public function getcancelAction($idAutoresponder)
	{
		if($idAutoresponder) {
			$autoresponder = Autoresponder::findFirst(array(
					"conditions" => "idAutoresponder = ?1",
					"bind" => array(1 => $idAutoresponder)
				));

			switch ($autoresponder->type) {
				case 'birthday' :
					return $this->setJsonResponse(array('status' => "birthday"), 201, 'Success');
					break;
			}
		}
	}
}