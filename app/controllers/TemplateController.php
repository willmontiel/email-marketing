<?php
class TemplateController extends ControllerBase
{	
	public function newAction()
	{
		if ($this->request->isPost()) {
			$content = $this->request->getPost("editor");
			$name = $this->request->getPost("name");
			$category = $this->request->getPost("category");
			
			if (empty($content) || empty($name) || empty($category)) {
				return $this->setJsonResponse(array('msg' => 'Ha enviado campos vacíos, por favor verifique la información'), 404, 'failed');
			}
			
			try {
				$template = new TemplateObj();
				$templateDone = $template->createTemplate($name, $category, $content);
			}
			catch (InvalidArgumentException $e) {
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error'), 404, 'failed');
			}
			
			if ($templateDone) {
				return $this->setJsonResponse(array('msg' => 'Se ha creado la cuenta exitosamente'), 200, 'success');
			}
		}
	}
	
	public function editor_frameAction() 
	{
		if (!$this->request->isPost()) {
			
		$assets = AssetObj::findAllAssetsInAccount($this->user->account);
		
		foreach ($assets as $a) {
			$arrayAssets[] = array ('thumb' => $a->getThumbnailUrl(), 
								'image' => $a->getImagePrivateUrl(),
								'title' => $a->getFileName(),
								'id' => $a->getIdAsset());								
		}
		
		$this->view->setVar('assets', $arrayAssets);
		}
		else {
			$this->view->setVar('assets', $arrayAssets);
		}
		
		$cfs = Customfield::findAllCustomfieldNamesInAccount($this->user->account);
		
		foreach ($cfs as $cf) {
			$linkname = strtoupper(str_replace(array ("á", "é", "í", "ó", "ú", "ñ", " ", "&", ), 
											   array ("a", "e", "i", "o", "u", "n", "_"), $cf[0]));
			
			$arrayCf[] = array('originalName' => ucwords($cf[0]), 'linkName' => $linkname);
		}
		
		$this->view->setVar('cfs', $arrayCf);
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
		$img = $this->templatesfolder->dir . $idTemplate. "/images/" . $idTemplateImage . ".JPG";
	
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
			$log->log($img);
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
		
		if (empty($content) || empty($name)) {
			return $this->setJsonResponse(array('error' => 'Ha enviado campos vacíos, por favor verifique la información'), 404, 'failed');
		}
		
		$category = "Mis Templates";
		
		try {
			$template = new TemplateObj();
			$template->createTemplate($name, $category, $content, $this->user->account);
		}
		catch (InvalidArgumentException $e) {

		}
	}
}
