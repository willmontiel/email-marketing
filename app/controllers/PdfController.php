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
			
			if ($_FILES["source"]["error"]) {
				$this->flashSession->error('No ha enviado ningún archivo condificador(PHP) o ha enviado un tipo de archivo no soportado, contacte al administrador para más información');
				return $this->response->redirect('pdf/loadtemplate');
			}
			
			if (empty($_FILES['source']['name'])){
				$this->flashSession->error('No ha enviado ningún archivo condificador(PHP) o ha enviado un tipo de archivo no soportado, por favor verifique la información');
				return $this->response->redirect('pdf/loadtemplate');
			}
			
			$template = $_FILES['template'];
			$source = $_FILES['source'];
			
			$data1 = new stdClass();
			$data1->originalName = $template['name'];
			$data1->size = $template['size'];
			$data1->type = $template['type'];
			$data1->tmp_dir = $template['tmp_name'];
			$name2 = str_replace(" ", "_", "{$name}.xsl");
			$data1->name = strtolower($name2);
			$ext1 = array('xsl');

			$data2 = new stdClass();
			$data2->originalName = $source['name'];
			$data2->size = $source['size'];
			$data2->type = $source['type'];
			$data2->tmp_dir = $source['tmp_name'];
			$data2->name = $source['name'];
			$ext2 = array('php');
			
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
				
				$dir = "{$this->pdftemplates->folder}/{$pdf->idPdftemplate}/";
				$uploader = new \EmailMarketing\General\Misc\Uploader();
				
				$uploader->setData($data1);
				$uploader->validateExt($ext1);
				$uploader->validateSize(2000);
				$uploader->uploadFile($dir);
			
				$uploader->setData($data2);
				$uploader->validateExt($ext2);
				$uploader->validateSize(2000);
				$uploader->uploadFile($dir);

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
					$name2 = str_replace(" ", "_", "{$name}.xsl");
					$data->name = strtolower($name2);

					$ext = array('xsl');
				}
				
				try {
					if ($editfile == 1) {
						$uploader = new \EmailMarketing\General\Misc\Uploader();
						$uploader->setData($data);
						$uploader->validateExt($ext);
						$uploader->validateSize(2000);
						$uploader->uploadFile($dir);
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
		$file = 0;
		$files = glob($dir . "{*.xsl}",GLOB_BRACE);
		$name = str_replace(" ", '_', $pdf->name);
		$name = strtolower($name);
		$name = "{$name}.xsl";
		$dir = "{$dir}{$name}";
		
		if (in_array($dir, $files)) {
			$file = 1;
		}
		
		$this->view->setVar('pdf', $pdf);
		$this->view->setVar('file', $file);
		$this->view->setVar('name', $name);
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
				$name2 = str_replace(" ", "_", "{$name}.csv");
				$data->name = strtolower($name2);

				$ext = array('csv');
				
				$dir = "{$this->pdftemplates->sourcefolder}{$account->idAccount}/";
				$uploader = new \EmailMarketing\General\Misc\Uploader();
				$uploader->setData($data);
				$uploader->validateExt($ext);
				$uploader->validateSize(2000);
				$uploader->uploadFile($dir);

				$batch = new Pdfbatch();
				$batch->idAccount = $account->idAccount;
				$batch->name = $name;
				$batch->sourceName = $name2;
				$batch->createdon = time();
				
				if (!$batch->save()) {
					foreach ($batch->getMessages() as $m) {
						$this->logger->log("Error while saving pdftemplate: {$m}");
					}
					throw new Exception("Ocurrió mientras se intentaba iniciar el proceso de creación de lotes de PDF, por favor contacte al administrador");
				}

				$this->flashSession->success('Se ha cargado el template de PDF exitosamente');
				return $this->response->redirect('pdf');
			}
		}
		
		$this->view->setVar('templates', $t);
	}
}