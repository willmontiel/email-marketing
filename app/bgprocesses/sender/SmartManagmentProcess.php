<?php
class SmartManagmentProcess extends ProcessAbstract
{
	public function getPublisherToChildrenSocket()
	{
		return SocketConstants::getSmartmanagmentPub2ChildrenEndPoint();
	}

	public function getReplyToClientSocket()
	{
		return SocketConstants::getSmartmanagmentRequestsEndPoint();
	}

	public function getPullFromChildSocket()
	{
		return SocketConstants::getSmartmanagmentPullFromChildEndPoint();
	}
	
	public function setPoolConditions()
	{
		$this->pool->setInitialChildren(1);
		$this->pool->setMaxOfTmpChildren(0);
		$this->pool->setChildProcess('ChildSmartManagment.php');
		$this->pool->createInitialChildren();
	}
}