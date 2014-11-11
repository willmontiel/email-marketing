<?php
require_once '../bootstrap/phbootstrap.php';

$child = ChildProcess::getManagmentChild();

$child->startProcess();

class ChildSmartManagment extends ChildProcess
{
	public function executeProcess($data)
	{
		$this->pingDatabase();
		$arrayDecode = json_decode($data);
//		$exporter = new ContactExporter();
//		$exporter->setData($arrayDecode);
//		$exporter->startExporting();
	}
	
	public function publishToChildren()
	{
		return SocketConstants::getSmartmanagmentPub2ChildrenEndPoint();
	}

	public function pullFromChild()
	{
		return SocketConstants::getSmartmanagmentPullFromChildEndPoint();
	}
}

return 0;