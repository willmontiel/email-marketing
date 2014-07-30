<?php
class TemplateController extends ControllerBase
{	
	public function indexAction($idMail = null)
	{
		if ($idMail != null) {
			$mail = $this->validateProcess($idMail);
			$idMail = $mail->idMail;
		}
		
		$templates = Template::findGlobalsAndPrivateTemplates($this->user->account);

		$arrayTemplate = array();
		foreach ($templates as $template) {
			$templateInfo = array(
				"id" => $template->idTemplate, 
				"name" => $template->name, 
				"content" => $template->content,
				"html" => $template->contentHtml,
				"preview" => $template->previewData,
				"idMail" => $idMail,
				"idAccount" => $template->idAccount
			);
			$arrayTemplate[$template->category][] = $templateInfo;
		}

		$this->view->setVar('templates', $templates);
		$this->view->setVar('arrayTemplate', $arrayTemplate);
		$this->view->setVar('global_permissions', $this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
	}
	
	public function selectAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idMail,
							2 => $account->idAccount)
		));
		
		if (!$mail) {
			return $this->response->redirect('error');
		}
		
		try {
			$templates = Template::findGlobalsAndPrivateTemplates($account);
			$arrayTemplate = array();
			foreach ($templates as $template) {
				$templateInfo = array(
					"id" => $template->idTemplate, 
					"name" => $template->name, 
					"content" => $template->content,
					"html" => $template->contentHtml,
					"preview" => $template->previewData,
					"idMail" => $mail->idMail,
					"idAccount" => $template->idAccount
				);
				$arrayTemplate[$template->category][] = $templateInfo;
			}
			
			$this->view->setVar('mail', $mail);
			$this->view->setVar('templates', $templates);
			$this->view->setVar('arrayTemplate', $arrayTemplate);
		}
		catch (Exception $e) {
			$this->logger->log("Exception {$e}");
			return $this->response->redirect('error');
		}
	}




	protected function validateProcess($idMail)
	{
		$mail = Mail::findFirst(array(
			"conditions" => "idMail = ?1 AND idAccount = ?2 AND status = ?3",
			"bind" => array(1 => $idMail,
							2 => $this->user->account->idAccount,
							3 => "Draft")
		)); 
		if ($mail) {
			return $mail;
		}
		return null;
	}
	
	public function newAction()
	{
		if ($this->request->isPost()) {
			$content = $this->request->getPost("editor");
			$name = $this->request->getPost("name");
			$category = $this->request->getPost("category");
			$global = $this->request->getPost("global");
			
			$type = 'private';
			if ($global == 'true') {
				$type = 'public';
			}
			
			if (empty($content) || empty($name) || empty($category)) {
				return $this->setJsonResponse(array('msg' => 'Ha enviado campos vacíos (nombre, categoría o contenido), por favor verifique la información'), 400 , 'failed');
			}
			
			try {
				$template = new TemplateObj();
				$template->setGlobal($global);
				$template->setAccount($this->user->account);
				$template->setUser($this->user);
				$template->setSuperPermissions($this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
				$idTemplate = $template->createTemplate($name, $category, $content);
			}
			catch (Exception $e) {
				$this->logger->log("Exception: " . $e);
				$this->traceFail("Error creating {$type} template");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 500 , 'failed');
			}
			catch (InvalidArgumentException $e) {
				$this->logger->log("Exception: " . $e);
				$this->traceFail("Error creating {$type} template");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 500 , 'failed');
			}
			$this->traceSuccess("Create {$type} template, idTemplate: {$idTemplate}");
			return $this->setJsonResponse(array('idTemplate' => $idTemplate, 'msg' => 'Se ha creado la plantilla exitosamente'), 200, 'success');
		}
		else { 
			
			if ($this->validateRoleUser()) {
				$templates = Template::findGlobalsAndPrivateTemplates($this->user->account);
			}
			else {
				$templates = Template::findPrivateCategoryTemplates($this->user->account);
			}
					
			$arrayTemplate = array();
			foreach ($templates as $template) {
				if (!in_array($template->category, $arrayTemplate)) {
					$arrayTemplate[]= $template->category;
				}
			}
			$this->view->setVar('categories', $arrayTemplate);
		}		
		$this->view->setVar('global_permissions', $this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
	}
	
	public function previewAction($idTemplate)
	{
		$template = Template::findFirst(array(
			"conditions" => "idTemplate = ?1",
			"bind" => array(1 => $idTemplate)
		));
		return $this->setJsonResponse(array('template' => htmlspecialchars_decode($template->contentHtml)));
	}
	
	
	public function deleteAction($idTemplate)
	{
		$account = $this->user->account;
		
		$template = Template::findFirst(array(
			"conditions" => "idTemplate = ?1",
			"bind" => array(1 => $idTemplate)
		));
		
		$response = 'error';
		
		if ($template && $template->idAccount == '') {
			$response = $this->deletePublicTemplate($template);
		}
		else if ($template && $template->idAccount == $account->idAccount) {
			$response = $this->deletePrivateTemplate($template);
		}
		
		return $this->response->redirect($response);
	}
	
	private function deletePublicTemplate(Template $template)
	{
		if ($this->validateRoleUser()) {
			if (!$template->delete()) {
				foreach ($template->getMessages() as $msg) {
					$this->logger->log('Error: ' . $msg );
					$this->flashSession->error($msg);
				}
				return 'template';
			}
			$this->traceSuccess("Template public deleted by sudo. idTemplate: {$template->idTemplate}");
			$this->flashSession->warning("Se ha eliminado la plantilla exitosamente");
			return 'template';
		}
		return 'error';
	}
	
	private function deletePrivateTemplate(Template $template)
	{
		if (!$template->delete()) {
			foreach ($template->getMessages() as $msg) {
				$this->logger->log('Error: ' . $msg);
				$this->flashSession->error($msg);
			}
			return 'template';
		}
		$this->traceSuccess("Template private deleted. idTemplate: {$template->idTemplate}");
		$this->flashSession->warning("Se ha eliminado la plantilla exitosamente");
		return 'template';
	}

	private function validateRoleUser()
	{
		if ($this->acl->isAllowed($this->user->userrole, 'template', 'on any template')) {
			return true;
		}
		return false;
	}
	
	public function editAction($idTemplate)
	{
		$account = $this->user->account;
		
		$template = Template::findFirst(array(
			'conditions' => 'idTemplate = ?1',
			'bind' => array(1 => $idTemplate)
		));
		
		if ($this->request->isPost() && $template) {
			$name = $this->request->getPost("name");
			$category = $this->request->getPost("category");
			$global = $this->request->getPost("global");
			$content = $this->request->getPost("editor");
			
			$type = 'private';
			if ($global == 'true') {
				$type = 'public';
			}
			
			if (empty($content) || empty($name) || empty($category)) {
				return $this->setJsonResponse(array('msg' => 'Ha enviado campos vacíos, por favor verifique la información'), 400 , 'failed');
			}
			
			try {
				$templateObj = new TemplateObj();
				$templateObj->setAccount($account);
				$templateObj->setGlobal($global);
				$templateObj->setUser($this->user);
				$templateObj->setTemplate($template);
				$templateObj->setSuperPermissions($this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
				$templateObj->updateTemplate($name, $category, $content);
			}
			catch (Exception $e) {
				$this->traceFail("Edit {$type} template, idTemplate: {$idTemplate}");
				$this->logger->log('Exception: ' . $e);
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 500 , 'failed');
			}
			$this->traceSuccess("Edit {$type} template, idTemplate: {$idTemplate}");
			return $this->setJsonResponse(array('idTemplate' => $idTemplate, 'msg' => 'Se ha editado la plantilla exitosamente'), 200, 'success');
		}
		else {
			if ($template && $template->idAccount == '') {
				$this->logger->log('Editar plantilla global');
				if ($this->validateRoleUser()) {
					$templates = Template::findGlobalsAndPrivateTemplates($this->user->account);
				}
				else {
					return $this->response->redirect('error');
				}
			}
			else if ($template && $template->idAccount == $account->idAccount) {
				$this->logger->log('Editar plantilla pública');
				$templates = Template::findPrivateCategoryTemplates($this->user->account);
			}
			else {
				return $this->response->redirect('error');
			}
			
			$arrayTemplate = array();
			foreach ($templates as $t) {
				if (!in_array($t->category, $arrayTemplate)) {
					$arrayTemplate[] = $t->category;
				}
			}
			
			$this->view->setVar('template', $template);
			$this->view->setVar('categories', $arrayTemplate);
		}
		$this->view->setVar('global_permissions', $this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
	}
	
	public function previewtemplateAction()
	{
		$content = $this->request->getPost("editor");
		$this->session->remove('preview-template');
		$url = $this->url->get('template/createpreview');		
		$editorObj = new HtmlObj(true, $url);
		$editorObj->setAccount($this->user->account);
		$editorObj->assignContent(json_decode($content));
		$this->session->set('preview-template', $editorObj->render());
		
		return $this->setJsonResponse(array('status' => 'Success'), 201, 'Success');
	}

	public function previewdataAction()
	{
		$htmlObj = $this->session->get('preview-template');
		$this->session->remove('preview-template');

		$this->view->disable();
//		$this->logger->log($htmlObj);
		return $this->response->setContent($htmlObj);
	}
	
	public function createpreviewAction()
	{
		$content = $this->request->getPost("img");
		$imgObj = new ImageObject();
		$imgObj->createFromBase64($content);
		$imgObj->resizeImage(200, 300);
		$newImg = $imgObj->getImageBase64();
		
		$this->cache->save('preview-img64-cache-' . $this->user->idUser, $newImg, 7200);
	}
	
	
	public function imageAction($idTemplate, $idTemplateImage) 
	{
		$account = $this->user->account;
		
		$template = Template::findFirst(array(
			'conditions' => 'idTemplate = ?1',
			'bind' => array(1 => $idTemplate)
		));
		
		$image = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImage)
		));
		
		if ($template && $image && $this->validateTemplate($template, $account)) {
			
			$ext = pathinfo($image->name, PATHINFO_EXTENSION);
			
			if (empty($template->idAccount)) {
				$img = $this->templatesfolder->dir . $idTemplate. "/images/" . $idTemplateImage . "." . $ext;
			}
			else if ($template->idAccount == $account->idAccount) {
				$img = $this->asset->dir . $account->idAccount. "/templates/" . $template->idTemplate . '/images/' . $image->idTemplateImage . "." . $ext;
			}
			
			$this->logger->log($img);
			$info = getimagesize($img);
			$this->response->setHeader("Content-Type:", $info['mime']);
//			$this->response->setHeader("Content-Length:", filesize($img));
			$this->view->disable();
			return $this->response->setContent(file_get_contents($img));
		}
		return $this->setJsonResponse(array('status' => 'failed'), 404, 'template image not found!!');
	}
	
	private function validateTemplate($template, $account)
	{
		if ($template && $template->idAccount == null) {
			return true;
		}
		else if ($template && $template->idAccount == $account->idAccount) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public function thumbnailAction($idTemplate, $idAccount) 
	{
		$template = Template::findFirst(array(
			"conditions" => "idTemplate = ?1",
			"bind" => array(1 => $idTemplate)
		));
		
		if (!$template) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'template not found!!');
		}
		
		if($idAccount) {
			$img = $this->asset->dir . $idAccount . "/templates/" . $idTemplate . "/images/thumbnail_" . $idTemplate . ".png";
//			$log->log($img);
		}
		else {
			$img = $this->templatesfolder->dir . $idTemplate . "/images/thumbnail_" . $idTemplate . ".png";
		}
		
		$info = getimagesize($img);
		$this->response->setHeader("Content-Type:", $info['mime']);
		//$this->response->setHeader("Content-Length", filesize($img));
		
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function createAction() 
	{
		$content = $this->request->getPost("editor");
		$name = $this->request->getPost("name");
		$category = $this->request->getPost("category");

		if (empty($content) || empty($name)) {
			return $this->setJsonResponse(array('error' => 'Ha enviado campos vacíos, por favor verifique la información'), 404, 'failed');
		}
		
		$categoryF = !empty($category) ? $category : "Mis Templates";
		
		try {
			$template = new TemplateObj();
			$template->setAccount($this->user->account);
			$template->setUser($this->user);
			$template->setSuperPermissions($this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
			$template->createTemplate($name, $categoryF, $content);
			$this->traceSuccess("Create template");
		}
		catch (Exception $e) {
			$this->traceFail("Error create template");
			$this->logger->log("Exception: " . $e);
			$this->flashSession->error('Ha ocurrido un error, por favor conacte al administrador');
		}
		catch (InvalidArgumentException $e) {
			$this->logger->log("Exception: " . $e);
			$this->flashSession->error('Ha ocurrido un error, por favor conacte al administrador');
		}
	}
}
