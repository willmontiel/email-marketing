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
			$this->pingDatabase();
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

			$account = Account::findFirstByIdAccount($idAccount);
			$wrapper = new ImportContactWrapper();

			$wrapper->setIdProccess($idImportproccess);
			$wrapper->setIdContactlist($idContactlist);
			$wrapper->setAccount($account);
			$wrapper->setIpaddress($ipaddress);
			$wrapper->startImport($fields, $destiny, $dateformat, $delimiter, $header, $importmode);
		} catch (Exception $ex) {
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

