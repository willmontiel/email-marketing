<?php
require_once '../bootstrap/phbootstrap.php';

$child = ChildProcess::getExportChild();

$child->startProcess();

class ChildExport extends ChildProcess
{
	public function executeProcess($data)
	{
		$log = \Phalcon\DI::getDefault()->get('logger');
		try {
			$this->pingDatabase();
			$arrayDecode = json_decode($data);
			$exporter = new ContactExporter();
			$exporter->setData($arrayDecode);
			$exporter->startExporting();
		} catch (Exception $ex) {
			$log->log('Error exporting contacts ' . $ex);
		}
	}
	
	public function publishToChildren()
	{
		return SocketConstants::getExportPub2ChildrenEndPoint();
	}

	public function pullFromChild()
	{
		return SocketConstants::getExportPullFromChildEndPoint();
	}
}

return 0;