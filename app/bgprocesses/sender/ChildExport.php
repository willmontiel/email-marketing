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
			$log->log('Inicio proceso de Exportacion');
			$log->log('Recibi esto [ ' . $data . ' ]');
			$log->log('***----------------------------***');
			$log->log('***----------------------------***');
			$log->log('Inicio Ping a la Base de Datos');
			$this->pingDatabase();
			$log->log('Finalizacion Ping a la Base de Datos');
			$log->log('Inicio Instancia a ContactExporter');
			$arrayDecode = json_decode($data);
			$exporter = new ContactExporter();
			$log->log('Finalizacion Instancia a ContactExporter');
			$log->log('Inicio Seteo de Data');
			$exporter->setData($arrayDecode);
			$log->log('Finalizacion Seteo de Data');
			$log->log('Inicio Proceso de Data');
			$exporter->startExporting();
			$log->log('Finalizacion Proceso de Data');
			$log->log('***----------------------------***');
			$log->log('***----------------------------***');
			$log->log('Finalizacion proceso de Exportacion');
		} catch (\Exception $ex) {
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