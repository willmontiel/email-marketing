<?php
class TemplateController extends ControllerBase
{	
	public function newAction()
	{
		if ($this->request->isPost()) {
			$content = $this->request->getPost("editor");
			$name = $this->request->getPost("name");
			$category = $this->request->getPost("category");
			$global = $this->request->getPost("global");
			
			if (empty($content) || empty($name) || empty($category)) {
				return $this->setJsonResponse(array('msg' => 'Ha enviado campos vacíos, por favor verifique la información'), 400 , 'failed');
			}
			
			if (($this->user->userrole == "ROLE_SUDO") && ($global == 'true')) {
				$account = null;
			}
			else {
				$account = $this->user->account;
			}
			
			try {
				$template = new TemplateObj();
				$templateDone = $template->createTemplate($name, $category, $content, $account);
			}
			catch (InvalidArgumentException $e) {
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 500 , 'failed');
			}
			
			if ($templateDone) {
				$this->flashSession->success('Se ha creado la plantilla exitosamente');
				return $this->setJsonResponse(array('msg' => 'Se ha creado la plantilla exitosamente'), 200, 'success');
			}
		}
		else { 
			
			if ($this->user->userrole == "ROLE_SUDO") {
				$templates = Template::findGlobalCategoryTemplates();
			}
			else {
				$templates = Template::findPrivateCategoryTemplates($this->user->account);
			}
					
			$arrayTemplate = array();
			foreach ($templates as $template) {
				$arrayTemplate[]= $template->category;
			}
			
			$this->view->setVar('categories', $arrayTemplate);
		}		
	}
	
	public function previewAction($id)
	{
		$template = Template::findFirst(array(
			"conditions" => "idTemplate = ?1",
			"bind" => array(1 => $id)
		));
		return $this->setJsonResponse(array('template' => $template->contentHtml));
	}
	
	public function imageAction($idTemplate, $idTemplateImage) 
	{
		$tpImg = Templateimage::findFirst(array(
			'conditions' => 'idTemplateImage = ?1',
			'bind' => array(1 => $idTemplateImage)
		));
		
		$ext = pathinfo( $tpImg->name, PATHINFO_EXTENSION);
		$img = $this->templatesfolder->dir . $idTemplate. "/images/" . $idTemplateImage . "." . $ext;
	
		$info = getimagesize($img);
		$this->response->setHeader("Content-Type:", $info['mime']);
//		$this->response->setHeader("Content-Length:", filesize($img));
		
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
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
		$log = $this->logger;
		$content = $this->request->getPost("editor");
		$name = $this->request->getPost("name");
		$category = $this->request->getPost("category");

		if (empty($content) || empty($name)) {
			return $this->setJsonResponse(array('error' => 'Ha enviado campos vacíos, por favor verifique la información'), 404, 'failed');
		}
		
		$categoryF = !empty($category) ? $category : "Mis Templates";
		
		try {
			$template = new TemplateObj();
			$template->createTemplate($name, $categoryF, $content, $this->user->account);
		}
		catch (InvalidArgumentException $e) {
			$this->flashSession->error('Ha ocurrido un error, por favor conacte al administrador');
		}
	}
}
