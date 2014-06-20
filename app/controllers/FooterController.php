<?php

class FooterController extends ControllerBase {
	
	public function indexAction()
	{
		$footers = Footer::find();
		$this->view->setVar('footers', $footers);
	}
	
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
			$name = trim($this->request->getPost("name"));
			if(empty($name)) {
				return $this->setJsonResponse(array('msg' => 'El nombre que ha enviado es inválido o esta vacío, por favor verifique la información'), 400 , 'failed');
			}
			$content = $this->request->getPost("content");
			try {
				$obj = new FooterObj();
				$obj->createFooter($content, $name);
			} 
			catch(Exception $e) {
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error, contacta al administrador'), 500 , 'failed');
			}
		 }
	}
	
	public function previeweditorAction()
	{
		if ($this->request->isPost()) {
			$content = $this->request->getPost("editor");
			$this->session->remove('preview-template');
			$url = $this->url->get('template/createpreview');		
			$editorObj = new HtmlObj(true, $url);
			$editorObj->assignContent(json_decode($content));
			$this->session->set('preview-template', $editorObj->render());

			return $this->setJsonResponse(array('status' => 'Success'), 201, 'Success');
		 }
	}
	
	public function previewdataAction()
	{
		$htmlObj = $this->session->get('preview-template');
		$this->session->remove('preview-template');
		$this->view->disable();
		
		return $this->response->setContent($htmlObj);
	}
	
	public function editAction($idFooter)
	{
		$footer = Footer::findFirst(array(
			"conditions" => "idFooter = ?1",
			"bind" => array(1 => $idFooter)
		));
		
		$obj = new FooterObj();
		
		if ($this->request->isPost()) {
			$name = trim($this->request->getPost("name"));
			
			if(empty($name)) {
				return $this->setJsonResponse(array('msg' => 'El nombre que ha enviado es inválido o esta vacío, por favor verifique la información'), 400 , 'failed');
			}
			$content = $this->request->getPost("content");
			try {
				$obj->setFooter($footer);
				$obj->updateFooter($content, $name);
			} 
			catch(Exception $e) {
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error, contacta al administrador'), 500 , 'failed');
			}
			catch(InvalidArgumentException $e) {
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('msg' => 'Ha ocurrido un error, contacta al administrador'), 500 , 'failed');
			}
		}
		
		$objMail = $obj->setFooterEditorObj(json_decode($footer->editor));
		
		$this->view->setVar('objMail', $objMail);
		$this->view->setVar('footer', $footer);
	}
	
	public function deleteAction($idFooter)
	{
		$account = Account::findFirst(array(
			"conditions" => "idFooter = ?1",
			"bind" => array(1 => $idFooter)
		));
		
		if(!$account) {
			$footer = Footer::findFirst(array(
				"conditions" => "idFooter = ?1",
				"bind" => array(1 => $idFooter)
			));

			if( !$footer->delete() ) {
				$this->flashSession->warning("Error al eliminar el Footer");
			}
			else {
				$this->flashSession->warning("Se ha eliminado el Footer con éxito");
			}
		}
		else {
			$this->flashSession->error("No se pudo eliminar el Footer, ya que está asociado a una cuenta");
		}
		
		return $this->response->redirect("footer");
	}
	
	public function duplicateAction($idFooter)
	{
		$footer = Footer::findFirst(array(
			"conditions" => "idFooter = ?1",
			"bind" => array(1 => $idFooter)
		));
		
		if($footer) {
			try {
				$obj = new FooterObj();
				$newfooter = $obj->cloneContent($footer);
				return $this->response->redirect("footer/edit/{$newfooter->idFooter}");
			} 
			catch(Exception $e) {
				$this->logger->log("Exception: {$e}");
				$this->flashSession->error("No se pudo duplicar el Footer");
			}
			catch(InvalidArgumentException $e) {
				$this->logger->log("Exception: {$e}");
				$this->flashSession->error("No se pudo duplicar el Footer");
			}
			return $this->response->redirect("footer");
		}
	}
	
	public function imageAction($idFooter, $idFooterImage)
	{
		$image = Footerimage::findFirst(array(
			"conditions" => "idFooterImage = ?1 AND idFooter = ?2",
			"bind" => array(1 => $idFooterImage,
							2 => $idFooter)
		));
		
		if (!$image) {
			return $this->setJsonResponse(array('Error' => 'not found'), 404, 'No se encontro la imágen!!');
		}
		
		$ext = pathinfo($image->name, PATHINFO_EXTENSION);
		
		$img = $this->footersfolder->dir . "global/" . $image->idFooterImage . "." .$ext;
		
		$info = getimagesize($img);
		$this->response->setHeader("Content-Type:", $info['mime']);
		$this->logger->log('Imagen ' . $img);
		$this->view->disable();
		return $this->response->setContent(file_get_contents($img));
	}
}

?>
