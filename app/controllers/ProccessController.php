<?php

class ProccessController extends ControllerBase
{
    public function indexAction()
    {
       
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