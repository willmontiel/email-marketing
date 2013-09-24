<?php

class ProccessController extends ControllerBase
{
    public function indexAction()
    {
       
    }
	
	public function showAction($idImportproccess)
	{
		$newproccess = Importproccess::findFirstByIdImportproccess($idImportproccess);
		$inputFile = Importfile::findFirstByIdImportfile($newproccess->inputFile);
		
		$count = array(
			"linesprocess" => $newproccess->processLines,
			"exist" => $newproccess->exist,
			"invalid" => $newproccess->invalid,
			"bloqued" => $newproccess->bloqued,
			"limit" => $newproccess->limitcontact,
			"repeated" => $newproccess->repeated
		);
		
		$res = array(
			"name" => $inputFile->originalName,
			"totalReg" => $newproccess->totalReg,
			"status" => $newproccess->status,
			"linesprocess" => $count['linesprocess'],
			"import" => $count['linesprocess'] - ($count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated']),
			"Nimport" => $count['exist'] + $count['invalid'] + $count['bloqued'] + $count['limit'] + $count['repeated'],
			"exist" => $count['exist'],
			"invalid" => $count['invalid'],
			"bloqued" => $count['bloqued'],
			"limit" => $count['limit'],
			"repeated" => $count['repeated'],
			"idProcces" => $newproccess->idImportproccess
		);
		
		$this->view->setVar("res", $res);
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
}