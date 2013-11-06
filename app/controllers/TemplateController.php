<?php
class TemplateController extends ControllerBase
{	
	public function imageAction($idTemplate, $idGlobalAsset) 
	{
		$log = $this->logger;
		$img = $this->globalasset->dir . $idTemplate. "/images/" . $idGlobalAsset . ".JPG";
		
//		$log->log("Url: " . $img);
//		$log->log("Size: " . filesize($img));
		$info = getimagesize($img);
		$this->response->setHeader("Content-Type:", $info['mime']);
//		$this->response->setHeader("Content-Length:", filesize($img));
		
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
	
	public function thumbnailAction($idTemplate) 
	{
		$log = $this->logger;
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
