<?php

class ProcessController extends ControllerBase
{
    public function indexAction()
    {
       
    }
	
	public function getprocessesAction()
    {
		$status = array();
		$status['mail'] = $this->getSendingProcesses();
		$status['import'] = $this->getImportProcesses();
		return $this->setJsonResponse($status);
    }
	
	public function importAction()
	{
		$processes = Importproccess::find(array(
			"conditions" => "idAccount = ?1",
			"bind" => array(1 => $this->user->account->idAccount),
			"order" => "idImportproccess DESC"
		));
		
		$ago = strtotime('-90 days');
		$result = array();
		
		foreach ($processes as $process) {
			$inputFile = Importfile::findFirst(array(
				"conditions" => 'idImportfile = ?1 AND createdon > ?2',
				"bind" => array(1 => $process->inputFile,
								2 => $ago)
			));
			
			if ($inputFile) {
				$result[] = array(
					"name" => $inputFile->originalName,
					"status" => $process->status,
					"idProcess" => $process->idImportproccess
				);	
			}
		}
		
		$this->view->setVar("result", $result);
	}
	
	public function exportAction($idExport)
	{
		$account = $this->user->account;
		
		$export = Exportfile::findFirst(array(
			'conditions' => 'idExportfile = ?1 AND idAccount = ?2',
			'bind' => array(1 => $idExport,
							2 => $account->idAccount)
		));
		
		if (!$export) {
			return $this->response->redirect('error');
		}
		
		$data = $this->validateCriteria($account, $export->criteria, $export->idCriteria);
		
		$this->view->setVar('export', $export);
		$this->view->setVar('model', $data->model);
		$this->view->setVar('criteria', $data->criteria);
	}
	
	public function resfreshexportAction($idExport)
	{
		
	}
	
	private function validateCriteria($account, $criteria, $id)
	{
		switch ($criteria) {
			case 'contactlist':
				$name = 'Lista de contactos';
				
				$model = Contactlist::findFirst(array(
					'conditions' => 'idContactlist = ?1',
					'bind' => array(1 => $id)
				));

				$dbase = Dbase::findFirst(array(
					'conditions' => 'idDbase = ?1 AND idAccount = ?2',
					'bind' => array(1 => $model->idDbase,
									2 => $account->idAccount)
				));

				if (!$model || !$dbase) {
					throw new InvalidArgumentException("No existe criterio");
				}
				break;

			case 'dbase':
				$name = 'Base de datos';
				
				$model = Dbase::findFirst(array(
					'conditions' => 'idDbase = ?1 AND idAccount = ?2',
					'bind' => array(1 => $id,
									2 => $account->idAccount)
				));

				if (!$model) {
					throw new InvalidArgumentException("No existe criterio");
				}
				break;

			case 'segment':
				$name = 'Segmento';
				
				$model = Segment::findFirst(array(
					'conditions' => 'idSegment = ?1',
					'bind' => array(1 => $id)
				));

				$dbase = Dbase::findFirst(array(
					'conditions' => 'idDbase = ?1 AND idAccount = ?2',
					'bind' => array(1 => $model->idDbase,
									2 => $account->idAccount)
				));

				if (!$model || !$dbase) {
					throw new InvalidArgumentException("No existe criterio");
				}
				break;
		}
		
		$data = new stdClass();
		$data->model = $model;
		$data->criteria = $name;
		
		return $data;
	}
	
	public function refreshimportAction($idImportprocess)
	{
		$res = $this->getImportInfo($idImportprocess);
		
		if (!$res) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontró información de importación');
		}
		
		return $this->setJsonResponse($res);
	}
	
	public function downoladsuccessAction($idProcess)
	{
		$this->view->disable();
		
		$process = Importproccess::findFirstByIdImportproccess($idProcess);
		$successProcess = Importfile::findFirstByIdImportfile($process->successFile);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=ContactosImportados.csv');
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Type: application/download');
		echo "Contactos Importados".PHP_EOL;
		readfile("../tmp/ifiles/".$successProcess->internalName);
	}
	
	public function downoladerrorAction($idProcess)
	{
		$this->view->disable();
		
		$process = Importproccess::findFirstByIdImportproccess($idProcess);
		$errorProcess = Importfile::findFirstByIdImportfile($process->errorFile);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=ContactosNoImportados.csv');
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Type: application/download');
		echo "Contactos No Importados".PHP_EOL;
		readfile("../tmp/ifiles/".$errorProcess->internalName);
	}
	
	public function getSendingProcesses()
	{
		try {
			$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$status = $communication->getStatus('Mail');
			if ($status !== null) {
				return $status;
			}
			return null; 
		}
		catch (Exception $e) {
			$this->logger->log("Exception: Error while getting import estatus, {$e}");
		}
	}
	
	public function getImportProcesses()
	{
		try {
			$communication = new Communication(SocketConstants::getImportRequestsEndPointPeer());
			$status = $communication->getStatus('Import');
			if ($status !== null) {
				return $status;
			}
			return null; 
		}
		catch (Exception $e) {
			$this->logger->log("Exception: Error while getting import estatus, {$e}");
		}
	}
	
	public function stopsendingAction($idTask)
	{
		if ($this->validateSendingMail($idTask)) {
			try {
				$communication = new Communication(SocketConstants::getMailRequestsEndPointPeer());
				$communication->sendPausedToParent($idTask);

				sleep(1);
				$this->flashSession->warning("Se ha pausado el envío del correo con id: {$idTask}");
				$this->traceSuccess("Stopping send, idMail: {$idTask}");
			}
			catch (Exception $e) {
				$this->traceFail("Stopping send, idMail: {$idTask}");
				$this->logger->log("Exception: Error while stopping send, {$e}");
				$this->flashSession->error("Ha ocurrido un error, contacte al administrador");
				return $this->response->redirect('process');
			}
		}
		else {
			$this->flashSession->error("Ha intentado pausar un envío que no se esta ejecutando, por favor verifique la información");
		}
		return $this->response->redirect('process');
	}
	
	public function stopimportAction($idTask)
	{
		if ($this->validateImport($idTask)) {
			try {
				$communication = new Communication(SocketConstants::getImportRequestsEndPointPeer());
				$communication->sendPausedImportToParent($idTask);
				sleep(1);
				$this->flashSession->warning("Se ha pausado el proceso de importación exitosamente");
				$this->traceSuccess("Stopping import, idImport: {$idTask}");
			}
			catch (Exception $e) {
				$this->traceFail("Stopping import, idImport: {$idTask}");
				$this->logger->log("Exception: Error while stopping import, {$e}");
				$this->flashSession->error("Ha ocurrido un error, por favor contacte al administrador");
				return $this->response->redirect('process');
			}
		}
		else {
			$this->flashSession->error("Ha intentado parar una importación que no se está ejecutando, por favor verifique la información");
		}
		return $this->response->redirect('process');
	}	
	
	private function validateMail($idMail) 
	{
		if ($this->acl->isAllowed($this->user->userrole, 'mail', 'on any mail')) {
			$mail = Mail::findFirst(array(
				'conditions' => "idMail = ?1 AND status = 'Sending'",
				'bind' => array(1 => $idMail)
			));
		}
		else {
			$mail = Mail::findFirst(array(
				'conditions' => "idMail = ?1 AND idAccount = ?2 AND deleted = 0 AND status != 'Sending'",
				'bind' => array(1 => $idMail,
								2 => $this->user->account->idAccount)
			));
		}
		
		return $mail;
	}
	
	private function validateSendingMail($idMail) 
	{
		if ($this->acl->isAllowed($this->user->userrole, 'mail', 'on any mail')) {
			$mail = Mail::findFirst(array(
				'conditions' => "idMail = ?1 AND status = 'Sending'",
				'bind' => array(1 => $idMail)
			));
		}
		else {
			$mail = Mail::findFirst(array(
				'conditions' => "idMail = ?1 AND idAccount = ?2 AND deleted = 0 AND status = 'Sending'",
				'bind' => array(1 => $idMail,
								2 => $this->user->account->idAccount)
			));
		}
		
		return $mail;
	}
	
	private function validateImport($idImport)
	{
		if ($this->acl->isAllowed($this->user->userrole, 'contact', 'on any import')) {
			$import = Importproccess::findFirst(array(
				'conditions' => "idImportproccess = ?1",
				'bind' => array(1 => $idImport)
			));
		}
		else {
			$import = Importproccess::findFirst(array(
				'conditions' => "idImportproccess = ?1 AND idAccount = ?2",
				'bind' => array(1 => $idImport,
								2 => $this->user->account->idAccount)
			));
		}
		
		return $import;
	}
	
	protected function getImportInfo($idImportprocess)
	{
		$process = Importproccess::findFirst(array(
			"conditions" => "idImportproccess = ?1",
			"bind" => array(1 => $idImportprocess),
		));
		
		$inputFile = Importfile::findFirstByIdImportfile($process->inputFile);
		
		$res = array();
		if ($process && $inputFile) {
			$count = array(
				"linesprocess" => $process->processLines,
				"exist" => $process->exist,
				"invalid" => $process->invalid,
				"bloqued" => $process->bloqued,
				"limit" => $process->limitcontact,
				"repeated" => $process->repeated
			);

			$res = array(
				"name" => $inputFile->originalName,
				"totalReg" => $process->totalReg,
				"status" => $process->status,
				"linesprocess" => $count['linesprocess'],
				"import" => $count['linesprocess'] - ($count['exist'] + $count['invalid'] + $count['bloqued'] + $count['repeated']),
				"Nimport" => $count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated'],
				"exist" => $count['exist'],
				"invalid" => $count['invalid'],
				"bloqued" => $count['bloqued'],
				"limit" => $count['limit'],
				"repeated" => $count['repeated'],
				"idProcess" => $process->idImportproccess
			);
		}
		
		return $res;
	}


	public function importdetailAction($idImportprocess)
	{
		$res = $this->getImportInfo($idImportprocess);
		
		if (!$res) {
			return $this->response->redirect("error");
		}
		
		$this->view->setVar("process", $res);
	}
	
	public function getexportinfoAction()
	{
		
	}
}