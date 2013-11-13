<?php
class TemplateController extends ControllerBase
{	
	public function newAction()
	{
		$log = $this->logger;
		if ($this->request->isPost()) {
			$content = $this->request->getPost("editor");
			$name = $this->request->getPost("name");
			$category = $this->request->getPost("category");
			
			if (empty($content) || empty($name) || empty($category)) {
				return $this->setJsonResponse(array('error' => 'Ha enviado campos vacíos, por favor verifique la información'), 404, 'failed');
			}
			
			try {
				$template = new TemplateObj();
				$template->createTemplate($name, $category, $content);
			}
			catch (InvalidArgumentException $e) {
				
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
	
	public function thumbnailAction($idTemplate) 
	{
		$template = Template::findFirst(array(
			"conditions" => "idTemplate = ?1",
			"bind" => array(1 => $idTemplate)
		));
		
		if (!$template) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'template not found!!');
		}
		
		$img = $this->globalasset->dir . $idTemplate . "/images/thumbnail_" . $idTemplate . ".png";
//		$log->log("Url: " . $img);
		$info = getimagesize($img);
		$this->response->setHeader("Content-Type:", $info['mime']);
		$this->response->setHeader("Content-Length", filesize($img));
		
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
}
