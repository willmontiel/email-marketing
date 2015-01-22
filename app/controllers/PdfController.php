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
				return $this->response->redirect('pdf/loadtemplate');
			}
			
			if (count($accounts) <= 0) {
				$this->flashSession->error('Debe seleccionar al menos una cuenta, por favor verifique la información');
				return $this->response->redirect('pdf/loadtemplate');
			}
			
			if ($_FILES["template"]["error"]) {
				$this->flashSession->error('No ha enviado ningún archivo de template(XSL) o ha enviado un tipo de archivo no soportado, contacte al administrador para más información');
				return $this->response->redirect('pdf/loadtemplate');
			}
			
			if (empty($_FILES['template']['name'])){
				$this->flashSession->error('No ha enviado ningún archivo de template(XSL) o ha enviado un tipo de archivo no soportado, por favor verifique la información');
				return $this->response->redirect('pdf/loadtemplate');
			}
			
			$template = $_FILES['template'];
			
			$data = new stdClass();
			$data->originalName = $template['name'];
			$data->size = $template['size'];
			$data->type = $template['type'];
			$data->tmp_dir = $template['tmp_name'];
			$ext = array('zip');
			try {
				$this->db->begin();
				
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
				
				$data->name = "{$pdf->idPdftemplate}.zip";
				
				$dir = "{$this->pdf->templates}/{$pdf->idPdftemplate}/";
				
				$uploader = new \EmailMarketing\General\Misc\Uploader();
				$uploader->setData($data);
				$uploader->validateExt($ext);
				$uploader->validateSize(2000);
				$uploader->uploadFile($dir);
				$uploader->decompressFile("{$dir}{$data->name}", $dir);
				$uploader->changeNameOfFile("{$dir}template.xsl", "{$dir}{$pdf->idPdftemplate}.xsl");
				$uploader->deleteFileFromServer("{$dir}{$data->name}");
				
				$this->db->commit();
				
				$this->flashSession->success('Se ha cargado el template de PDF exitosamente');
				return $this->response->redirect('pdf');
			}
			catch (Exception $e) {
				$this->db->rollback();
				$this->logger->log("Exception: Error while uplodaing pdf {$e}");
				$this->flashSession->error("{$e->getMessage()}");
			}
		}
		
		$accounts = Account::find();
		$this->view->setVar('accounts', $accounts);
	}
	
	
	private function extractName($name)
	{
		$name = str_replace(" ", "_", "{$name}.xsl");
		$name = strtolower($name);
		
		return $name;
	}
	
	public function editAction($id)
	{
		$pdf = Pdftemplate::findFirst(array(
			'conditions' => 'idPdftemplate = ?1',
			'bind' => array(1 => $id)
		));
		
		$this->logger->log("PDF: {$pdf->name}");
		if (!$pdf) {
			$this->flashSession->error("El template que desea editar no se encuentra, por favor valide la información");
			return $this->response->redirect('pdf');
		}
		
		$dir = "{$this->pdftemplates->folder}";
		
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$accounts = $this->request->getPost('accounts');
			$editfile = $this->request->getPost('edit-file');
			
			if (empty($name)) {
				$this->flashSession->error('Debe envíar un nombre para la plantilla, por favor verifique la información');
			}
			else if (count($accounts) <= 0) {
				$this->flashSession->error('Debe seleccionar al menos una cuenta, por favor verifique la información');
			}
			else if ($editfile == 1 AND $_FILES["file"]["error"]) {
				$this->flashSession->error('No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, contacte al administrador para más información');
			}
			else if ($editfile == 1 AND empty($_FILES['file']['name'])){
				$this->flashSession->error('No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, por favor verifique la información');
			}
			else {
				if ($editfile == 1) {
					$data = new stdClass();
					$data->originalName = $_FILES['file']['name'];
					$data->size = $_FILES['file']['size'];
					$data->type = $_FILES['file']['type'];
					$data->tmp_dir = $_FILES['file']['tmp_name'];
					$data->name = "{$pdf->idPdftemplate}.zip";
					$ext = array('zip');
				}
				
				try {
					if ($editfile == 1) {
						$uploader = new \EmailMarketing\General\Misc\Uploader();
						$uploader->deleteFileFromServer("{$dir}{$pdf->idPdftemplate}.xsl");
						
						$uploader->setData($data);
						$uploader->validateExt($ext);
						$uploader->validateSize(2000);
						$uploader->uploadFile($dir);
						
						$uploader->decompressFile("{$dir}{$data->name}", $dir);
						$uploader->changeNameOfFile("{$dir}template.xsl", "{$dir}{$pdf->idPdftemplate}.xsl");
						$uploader->deleteFileFromServer("{$dir}{$data->name}");
					}
					
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
	
	public function createbatchAction()
	{
		$templates = Pdftemplate::find();
		$account = $this->user->account;
		$t = array();
		
		if (count($templates) > 0) {
			foreach ($templates as $template) {
				$ids = json_decode($template->idAccounts);
				if (in_array($account->idAccount, $ids)) {
					$t[] = $template;
				}
			}
		}
		
		if ($this->request->isPost()) {
			$name = $this->request->getPost('name');
			$template = $this->request->getPost('template');
			
			if (empty($name)) {
				$this->flashSession->error('Debe envíar un nombre para la plantilla, por favor verifique la información');
			}
			else if (empty($template)) {
				$this->flashSession->error('Debe seleccionar al menos una plantilla, por favor verifique la información');
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

				$ext = array('csv');
				
				try {
					$this->db->begin();
					
					$batch = new Pdfbatch();
					$batch->idAccount = $account->idAccount;
					$batch->idPdftemplate = $template;
					$batch->name = $name;
					$batch->status = 'iniciado';
					$batch->processed = 0;
					$batch->toProcess = 0;
					$batch->createdon = time();
					$batch->updatedon = time();

					if (!$batch->save()) {
						foreach ($batch->getMessages() as $m) {
							throw new Exception("Error while saving pdftemplate: {$m}");
						}
					}
					
					$dir = "{$this->pdf->relativecsvbatch}/{$account->idAccount}/";
					$data->name = "{$batch->idPdfbatch}.csv";
					
					$uploader = new \EmailMarketing\General\Misc\Uploader();
					$uploader->setData($data);
					$uploader->validateExt($ext);
					$uploader->validateSize(2000);
					$uploader->uploadFile($dir);

					$this->db->commit();
					
					$d = array(
						'idPdfbatch' => $batch->idPdfbatch,
					);

					$toSend = json_encode($d);
					$objcomm = new Communication(SocketConstants::getPdfCreatorRequestsEndPointPeer());
					$objcomm->sendImportToParent($toSend, $batch->idPdfbatch);
					
					return $this->response->redirect("process/pdfbatch/{$batch->idPdfbatch}");
				}
				catch (Exception $ex) {
					$this->db->rollback();
					$this->logger->log("Exception: {$ex}");
					$this->flashSession->error("Ocurrió mientras se intentaba iniciar el proceso de creación de lotes de PDF, por favor contacte al administrador");
					return $this->response->redirect('pdf');
				}
			}
		}
		
		$this->view->setVar('templates', $t);
	}
	
	public function getbatchAction($idBatch)
	{
		$account = $this->user->account;
		$batch = Pdfbatch::findFirst(array(
			'conditions' => 'idAccount = ?1 AND idPdfbatch = ?2',
			'bind' => array(1 => $account->idAccount,
							2 => $idBatch)
		));
		
		if (!$batch) {
			$this->flashSession->error("El archivo de lote de PDF no existe, por favor valide la información");
			return $this->response->redirect('error');
		}
		
		$this->view->disable();
		$file = "{$this->appPath->path}/{$this->pdf->encryptedbatch}/{$account->idAccount}/{$batch->idPdfbatch}.zip";
		header('Content-type: application/zip');
		header("Content-Disposition: attachment; filename={$batch->idPdfbatch}.zip");
		header('Content-Length: ' . filesize($file));
		readfile($file);
	}
}