<?php
require_once '../bootstrap/phbootstrap.php';

$child = ChildProcess::getImportChild();

$child->startProcess();

class ChildImport extends ChildProcess
{
	public function executeProcess($data)
	{
		$arrayDecode = json_decode($data);
	
		$idContactlist = $arrayDecode->idContactlist;
		$idImportproccess = $arrayDecode->idImportproccess;
		$fields = $arrayDecode->fields;
		$destiny = $arrayDecode->destiny;
		$delimiter = $arrayDecode->delimiter;
		$header = $arrayDecode->header;
		$idAccount = $arrayDecode->idAccount;
		$ipaddress = $arrayDecode->ipaddress;

		$account = Account::findFirstByIdAccount($idAccount);
		$wrapper = new ImportContactWrapper();

		$wrapper->setIdProccess($idImportproccess);
		$wrapper->setIdContactlist($idContactlist);
		$wrapper->setAccount($account);
		$wrapper->setIpaddress($ipaddress);
		$wrapper->startImport($fields, $destiny, $delimiter, $header);
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

