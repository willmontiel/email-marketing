<?php
require_once '../bootstrap/phbootstrap.php';


$child = ChildProcess::getSenderChild();

$child->startProcess();

class ChildSender extends ChildProcess
{
	public function executeProcess($data) 
	{
		$log = \Phalcon\DI::getDefault()->get('logger');
		try {
			$log->log('Inicio proceso de envio');
			$log->log('***----------------------------***');
			$log->log('***----------------------------***');
			$log->log('Inicio Ping a la Base de Datos');
			$this->pingDatabase();
			$log->log('Finalizacion Ping a la Base de Datos');
			$log->log('Inicio Instancia a ChildCommunication');
			$communication = new ChildCommunication();
			$log->log('Finalizacion Instancia a ChildCommunication');
			$log->log('Inicio Seteo de Socket');
			$communication->setSocket($this);
			$log->log('Finalizacion Seteo de Socket');
			$log->log('Inicio Proceso de Data');
			$communication->startProcess($data);
			$log->log('Finalizacion Proceso de Data');
			$log->log('***----------------------------***');
			$log->log('***----------------------------***');
			$log->log('Finalizacion proceso de envio');
		} catch (Exception $ex) {
			$log->log('Error sending mailing ' . $ex);
		}
	}

	public function publishToChildren()
	{
		return SocketConstants::getMailPub2ChildrenEndPoint();
	}

	public function pullFromChild()
	{
		return SocketConstants::getMailPullFromChildEndPoint();
	}	
}

return 0;