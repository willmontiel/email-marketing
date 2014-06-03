<?php
require_once '../bootstrap/phbootstrap.php';


$child = ChildProcess::getSenderChild();

$child->startProcess();

class ChildSender extends ChildProcess
{
	public function executeProcess($data) 
	{
		$this->pingDatabase();
		$communication = new ChildCommunication();
		$communication->setSocket($this);
		$communication->startProcess($data);
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