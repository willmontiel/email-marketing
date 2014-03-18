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
				"idProcces" => $process->idImportproccess
			);		
		}
		$this->view->setVar("result", $result);
	}
	
	public function refreshimportAction($idImportproccess)
	{
		$proccess = Importproccess::findFirst(array(
			"conditions" => "idImportproccess = ?1",
			"bind" => array(1 => $idImportproccess),
		));
		
		$inputFile = Importfile::findFirstByIdImportfile($proccess->inputFile);
		
		$res = array();
		if ($proccess && $inputFile) {
			$count = array(
				"linesprocess" => $proccess->processLines,
				"exist" => $proccess->exist,
				"invalid" => $proccess->invalid,
				"bloqued" => $proccess->bloqued,
				"limit" => $proccess->limitcontact,
				"repeated" => $proccess->repeated
			);

			$res = array(
				"name" => $inputFile->originalName,
				"totalReg" => $proccess->totalReg,
				"status" => $proccess->status,
				"linesprocess" => $count['linesprocess'],
				"import" => $count['linesprocess'] - ($count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated']),
				"Nimport" => $count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated'],
				"exist" => $count['exist'],
				"invalid" => $count['invalid'],
				"bloqued" => $count['bloqued'],
				"limit" => $count['limit'],
				"repeated" => $count['repeated'],
				"idProcces" => $proccess->idImportproccess
			);
		}
		
		return $this->setJsonResponse($res);
	}
	
	public function downoladsuccessAction($idProccess)
	{
		$this->view->disable();
		
		$proccess = Importproccess::findFirstByIdImportproccess($idProccess);
		$successProccess = Importfile::findFirstByIdImportfile($proccess->successFile);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=ContactosImportados.csv');
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Type: application/download');
		echo "Contactos Importados".PHP_EOL;
		readfile("../tmp/ifiles/".$successProccess->internalName);
	}
	
	public function downoladerrorAction($idProccess)
	{
		$this->view->disable();
		
		$proccess = Importproccess::findFirstByIdImportproccess($idProccess);
		$errorProccess = Importfile::findFirstByIdImportfile($proccess->errorFile);
		
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=ContactosNoImportados.csv');
		header('Pragma: public');
		header('Expires: 0');
		header('Content-Type: application/download');
		echo "Contactos No Importados".PHP_EOL;
		readfile("../tmp/ifiles/".$errorProccess->internalName);
	}
	
	public function getSendingProcesses()
	{
		$communication = new Communication();
		$status = $communication->getStatus('Mail');
		if ($status !== null) {
			return $status;
		}
		return null; 
	}
	
	public function getImportProcesses()
	{
		$communication = new Communication(null, SocketConstants::getImportRequestsEndPointPeer());
		$status = $communication->getStatus('Import');
		if ($status !== null) {
			return $status;
		}
		return null; 
	}
	
	public function stopsendingAction($idTask)
	{
		$communication = new Communication();
		
		$communication->sendPausedToParent($idTask);
		
		sleep(1);
		
		return $this->response->redirect('process');
	}
	
	public function stopimportAction($idTask)
	{
		$communication = new Communication(null, SocketConstants::getImportRequestsEndPointPeer());
		
		$communication->sendPausedImportToParent($idTask);
		
		sleep(1);
		
		return $this->response->redirect('process');
	}	
}