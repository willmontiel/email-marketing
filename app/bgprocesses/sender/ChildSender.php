<?php
require_once '../bootstrap/phbootstrap.php';


$child = ChildProcess::getSenderChild();

$child->startProcess();

class ChildSender extends ChildProcess
{
	public function executeProcess($data) 
	{
		try {
			$this->pingDatabase();
			$communication = new ChildCommunication();
			$communication->setSocket($this);
			$communication->startProcess($data);
		} catch (Exception $ex) {
			$log = \Phalcon\DI::getDefault()->get('logger');
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