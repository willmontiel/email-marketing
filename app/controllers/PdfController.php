<?php

class PdfController extends ControllerBase
{
	public function indexAction()
	{
		$pdfs = Pdftemplate::find();
		$a = array();
		if (count($pdfs) > 0) {
			foreach ($pdfs as $pdf) {
				$ids = json_decode($pdf->idAccounts);
				$ids = implode(',', $ids);
				
				$query = $this->db->query("SELECT * FROM account WHERE idAccount IN ({$ids})");
				$accounts = $query->fetchAll();
				
				if (count($accounts) > 0) {
					$a[$pdf->idPdftemplate] = $accounts;
				}
			}
		}
		
		$this->view->setVar('pdfs', $pdfs);
		$this->view->setVar('accounts', $a);
	}
	
	public function loadtemplateAction()
	{
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$accounts = $this->request->getPost('accounts');
			
			if (empty($name)) {
				$this->flashSession->error('Debe envíar un nombre para la plantilla, por favor verifique la información');
			}
			else if (count($accounts) <= 0) {
				$this->flashSession->error('Debe seleccionar al menos una cuenta, por favor verifique la información');
			}
			else if ($_FILES["file"]["error"]) {
				$this->flashSession->error('No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, contacte al administrador para más información');
			}
			else if (empty($_FILES['file']['name'])){
				$this->flashSession->error('No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, por favor verifique la información');
			}
			else {
				$data = new stdClass();
				$data->originalName = $_FILES['file']['name'];
				$data->size = $_FILES['file']['size'];
				$data->type = $_FILES['file']['type'];
				$data->tmp_dir = $_FILES['file']['tmp_name'];
				$data->name = str_replace(" ", "_", "{$name}.xsl");
				
				$ext = array('xsl');
				
				try {
					$dir = "{$this->pdftemplates->folder}";
					
					$uploader = new \EmailMarketing\General\Misc\Uploader();
					$uploader->setData($data);
					$uploader->validateExt($ext);
					$uploader->validateSize(512000);
					$uploader->uploadFile($dir);
					
					$pdf = new Pdftemplate();
					$pdf->name = $name;
					$pdf->idAccounts = json_encode($accounts);
					$pdf->created = time();
					$pdf->updated = time();
					
					if (!$pdf->save()) {
						foreach ($pdf->getMessages() as $m) {
							$this->logger->log("Error while saving pdftemplate: {$m}");
						}
						throw new Exception("Ocurrió mientras se guardaba el template, por favor contacte al administrador");
					}
					
					$this->flashSession->success('Se ha cargado el template de PDF exitosamente');
					return $this->response->redirect('pdf');
				}
				catch (Exception $e) {
					$this->logger->log("Exception: Error while uplodaing pdf {$e}");
					$this->flashSession->error("{$e->getMessage()}");
				}
			}
		}
		
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
	}
	
	
	public function editAction($id)
	{
		$pdf = Pdftemplate::findFirst(array(
			'conditions' => 'idPdftemplate = ?1',
			'bind' => array(1 => $id)
		));
		
		if (!$pdf) {
			$this->flashSession->error("El template que desea editar no se encuentra, por favor valide la información");
			return $this->response->redirect('pdf');
		}
		
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$accounts = $this->request->getPost('accounts');
			
			if (empty($name)) {
				$this->flashSession->error('Debe envíar un nombre para la plantilla, por favor verifique la información');
			}
			else if (count($accounts) <= 0) {
				$this->flashSession->error('Debe seleccionar al menos una cuenta, por favor verifique la información');
			}
			else if ($_FILES["file"]["error"]) {
				$this->flashSession->error('No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, contacte al administrador para más información');
			}
			else if (empty($_FILES['file']['name'])){
				$this->flashSession->error('No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, por favor verifique la información');
			}
			else {
				$data = new stdClass();
				$data->originalName = $_FILES['file']['name'];
				$data->size = $_FILES['file']['size'];
				$data->type = $_FILES['file']['type'];
				$data->tmp_dir = $_FILES['file']['tmp_name'];
				$data->name = str_replace(" ", "_", "{$name}.xsl");
				
				$ext = array('xsl');
				
				try {
					$dir = "{$this->pdftemplates->folder}";
					
					$uploader = new \EmailMarketing\General\Misc\Uploader();
					$uploader->setData($data);
					$uploader->validateExt($ext);
					$uploader->validateSize(512000);
					$uploader->uploadFile($dir);
					
					$pdf = new Pdftemplate();
					$pdf->name = $name;
					$pdf->idAccounts = json_encode($accounts);
					$pdf->created = time();
					$pdf->updated = time();
					
					if (!$pdf->save()) {
						foreach ($pdf->getMessages() as $m) {
							$this->logger->log("Error while saving pdftemplate: {$m}");
						}
						throw new Exception("Ocurrió mientras se guardaba el template, por favor contacte al administrador");
					}
					
					$this->flashSession->success('Se ha cargado el template de PDF exitosamente');
					return $this->response->redirect('pdf');
				}
				catch (Exception $e) {
					$this->logger->log("Exception: Error while uplodaing pdf {$e}");
					$this->flashSession->error("{$e->getMessage()}");
				}
			}
		}
		
		$this->view->setVar('pdf', $pdf);
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
		$ids = json_decode($pdf->idAccounts);
		$this->view->setVar('ids', $ids);
	}
	
	public function deleteAction($id)
	{
		$pdf = Pdftemplate::findFirst(array(
			'conditions' => 'idPdftemplate = ?1',
			'bind' => array(1 => $id)
		));
		
		if (!$pdf) {
			$this->flashSession->error("El template que desea eliminar no se encuentra, por favor valide la información");
			return $this->response->redirect('pdf');
		}
		
		try {
			if (!$pdf->delete()) {
				foreach ($pdf->getMessages() as $m) {
					throw new Exception("Error while deleting template: {$m->getMessage()}");
				}
			}
			
			$this->flashSession->success("Se ha eliminado la plantilla exitosamente");
			return $this->response->redirect('pdf');
		} 
		catch (Exception $ex) {
			$this->logger->log("Exception: {$ex}");
			$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
			return $this->response->redirect('pdf');
		}
	}
}