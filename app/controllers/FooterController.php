<?php

class FooterController extends ControllerBase {
	
	public function previewAction($id)
	{
		$this->view->disable();
		$footer = Footer::findFirst(array(
			"conditions" => "idFooter = ?1",
			"bind" => array(1 => $id)
		));
		
		return $this->setJsonResponse(array('preview' =>  $footer->html));
	}
	
	public function newAction()
	{
		 if ($this->request->isPost()) {
			$name = $this->request->getPost("name");
			$content = $this->request->getPost("content");
			$this->logger->log($content);
		 }
	}
}

?>
