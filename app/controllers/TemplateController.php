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
			$this->view->setVar('global_permissions', $this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
		}		
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
			$this->view->setVar('global_permissions', $this->acl->isAllowed($this->user->userrole, 'template', 'on any template'));
		}
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
	
	public function thumbnailpreviewAction($id, $size)
	{
		try {
			$key = "thumbnailtemplate-{$id}-{$size}";
			$img = $this->cache->get($key);
			if (!$img) {
				
				$template = Template::findFirst(array(
					'conditions' => 'idTemplate = ?1',
					'bind' => array(1 => $id)
				));
				
				if ($template && !empty($template->previewData)) {
					$base64 = $template->previewData;
				}
				else {
					$base64 = "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TSE3/9GrRhDc9nZblSCkfjG4DZsMgr3qDkUzLlw/CGaZ0ZXa+9yuzxSIvZuYddiA9cMys6zfz0rw+qKdD0K7X34QhuPXhcbb/23ZYBAqVznVHBrZIjIF66oVh7f9ep/6ous/rxfd9OeL3nrP7Zf5zLSfhmNbNhiEmauXueXXhkaTMbwxDsawnfG43SvYTHFn1jsfhHPb+wjCnagXXSQIk3WU+nVmCq7ep5sOQnn8NoyhUW1ZazR5bBW3t876duaGRusWcrRf5jOCcN+GgrHFZApEuEFwJ69HsxPIyqTUrsP2qR9iucuuGIRmv7z2dl+79tsPxyaDUBa9x78jmP+v8ArW1xVrZ2bdpgjXDYLH9strL3h8uiVF4emJDs4R7NV7UBbBuGDHbcoQDCaT8ckVdr0gaPvx9mbCaYPwTxmo7YZAbTgIl1YvWNwfgrAYQdgSgrAYQdgSgrAYQdgSggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggAIggCI/15fX90/AHvx+vp6+B/P0IoVkReWAQAAAABJRU5ErkJggg==";
				}
				
				$this->logger->log("Here!");
				$this->logger->log("{$id}");
				$this->logger->log("{$size}");
				$size = explode('x', $size);
				$imgObj = new ImageObject();
				$imgObj->createFromBase64($base64);
				$imgObj->resizeImage($size[0], $size[1]);
				$img = $imgObj->getImageInMemory();
				$this->cache->save($key, $img);
			}
			$this->response->setHeader("Content-Type", 'image/png');
			$this->view->disable();
			return $this->response->setContent($img);
		}
		catch (Exception $e) {
			$this->logger->log("Exception: {$e}");
		}
	}
}
