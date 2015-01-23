<?php
require_once '../bootstrap/phbootstrap.php';

$child = ChildProcess::getPdfCreatorChild();

$child->startProcess();

class ChildPdfCreator extends ChildProcess
{
	public function executeProcess($data)
	{
		$this->pingDatabase();
		$arrayDecode = json_decode($data);
		$exporter = new PdfCreator();
		$exporter->setData($arrayDecode);
		$exporter->startProcess();
	}
	
	public function publishToChildren()
	{
		return SocketConstants::getPdfCreatorPub2ChildrenEndPoint();
	}

	public function pullFromChild()
	{
		return SocketConstants::getPdfCreatorPullFromChildEndPoint();
	}
}

return 0;