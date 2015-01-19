<?php
require_once '../bootstrap/phbootstrap.php';

$child = ChildProcess::getImportChild();

$child->startProcess();

class ChildImport extends ChildProcess
{
	public function executeProcess($data)
	{
		$log = \Phalcon\DI::getDefault()->get('logger');
		try {
			$log->log('Inicio proceso de Importacion');
			$log->log('Recibi esto [ ' . $data . ' ]');
			$log->log('***----------------------------***');
			$log->log('***----------------------------***');
			$log->log('Inicio Ping a la Base de Datos');
			$this->pingDatabase();
			$log->log('Finalizacion Ping a la Base de Datos');
			$log->log('Inicio Decodificacion y Asignacion');
			$arrayDecode = json_decode($data);
			$idContactlist = $arrayDecode->idContactlist;
			$idImportproccess = $arrayDecode->idImportproccess;
			$fields = $arrayDecode->fields;
			$destiny = $arrayDecode->destiny;
			$dateformat = $arrayDecode->dateformat;
			$delimiter = $arrayDecode->delimiter;
			$header = $arrayDecode->header;
			$idAccount = $arrayDecode->idAccount;
			$ipaddress = $arrayDecode->ipaddress;
			$importmode = (isset($arrayDecode->importmode))?$arrayDecode->importmode:'normal';
			$log->log('Finalizacion Decodificacion y Asignacion');
			$log->log('Inicio Instancia a ImportContactWrapper');
			$wrapper = new ImportContactWrapper();
			$log->log('Finalizacion Instancia a ImportContactWrapper');
			$log->log('Inicio Seteo de IDs');
			$wrapper->setIdProccess($idImportproccess);
			$wrapper->setIdContactlist($idContactlist);
			$wrapper->setIpaddress($ipaddress);
			$log->log('Finalizacion Seteo de IDs');
			$log->log('Inicio Seteo de Account');
			$account = Account::findFirstByIdAccount($idAccount);
			$wrapper->setAccount($account);
			$log->log('Finalizacion Seteo de Account');
			$log->log('Inicio Proceso de Data');
			$wrapper->startImport($fields, $destiny, $dateformat, $delimiter, $header, $importmode);
			$log->log('Finalizacion Proceso de Data');
			$log->log('***----------------------------***');
			$log->log('***----------------------------***');
			$log->log('Finalizacion proceso de Importacion');
			
		} catch (\Exception $ex) {
			$log->log('Error importing contacts ' . $ex);
		}
	}

	public function publishToChildren()
	{
		return SocketConstants::getImportPub2ChildrenEndPoint();
	}

	public function pullFromChild()
	{
		return SocketConstants::getImportPullFromChildEndPoint();
	}
}

return 0;

