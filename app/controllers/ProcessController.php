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
		
		$result = array();
		foreach ($processes as $process) {
			
			$inputFile = Importfile::findFirstByIdImportfile($process->inputFile);

			$count = array(
				"linesprocess" => $process->processLines,
				"exist" => $process->exist,
				"invalid" => $process->invalid,
				"bloqued" => $process->bloqued,
				"limit" => $process->limitcontact,
				"repeated" => $process->repeated
			);

			$result[] = array(
				"name" => $inputFile->originalName,
				"totalReg" => $process->totalReg,
				"status" => $process->status,
				"linesprocess" => $count['linesprocess'],
				"import" => $count['linesprocess'] - ($count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated']),
				"Nimport" => $count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated'],
				"exist" => $count['exist'],
				"invalid" => $count['invalid'],
				"bloqued" => $count['bloqued'],
				"limit" => $count['limit'],
				"repeated" => $count['repeated'],
				"idProcess" => $process->idImportproccess
			);		
		}
		$this->view->setVar("result", $result);
	}
	
	public function refreshimportAction($idImportprocess)
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
				"import" => $count['linesprocess'] - ($count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated']),
				"Nimport" => $count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated'],
				"exist" => $count['exist'],
				"invalid" => $count['invalid'],
				"bloqued" => $count['bloqued'],
				"limit" => $count['limit'],
				"repeated" => $count['repeated'],
				"idProcess" => $process->idImportproccess
			);
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
		if ($this->validateMail($idTask)) {
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
		if ($this->user->userrole == 'ROLE_SUDO') {
			$mail = Mail::findFirst(array(
				'conditions' => "idMail = ?1 AND status != 'Sending'",
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
	
	private function validateImport($idImport)
	{
		if ($this->user->userrole == 'ROLE_SUDO') {
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
	
	public function importdetailAction($idProcess)
	{
		$process = Importproccess::findFirst(array(
			"conditions" => "idAccount = ?1 AND idImportproccess = ?2",
			"bind" => array(1 => $this->user->account->idAccount,
							2 => $idProcess),
		));
		
		$result = array();
		
		if ($process) {
			$inputFile = Importfile::findFirstByIdImportfile($process->inputFile);

			$count = array(
				"linesprocess" => $process->processLines,
				"exist" => $process->exist,
				"invalid" => $process->invalid,
				"bloqued" => $process->bloqued,
				"limit" => $process->limitcontact,
				"repeated" => $process->repeated
			);

			$result[] = array(
				"name" => $inputFile->originalName,
				"totalReg" => $process->totalReg,
				"status" => $process->status,
				"linesprocess" => $count['linesprocess'],
				"import" => $count['linesprocess'] - ($count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated']),
				"Nimport" => $count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated'],
				"exist" => $count['exist'],
				"invalid" => $count['invalid'],
				"bloqued" => $count['bloqued'],
				"limit" => $count['limit'],
				"repeated" => $count['repeated'],
				"idProcess" => $process->idImportproccess
			);		
		}
		
		else {
			return $this->response->redirect("error");
		}
		
		$this->view->setVar("process", $result);
	}
}