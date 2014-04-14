<?php
class SenderProcess extends ProcessAbstract {
	
	public function getPublisherToChildrenSocket()
	{
		return SocketConstants::getMailPub2ChildrenEndPoint();
	}
	
	public function getReplyToClientSocket()
	{
		return SocketConstants::getMailRequestsEndPoint();
	}
	
	public function getPullFromChildSocket()
	{
		return SocketConstants::getMailPullFromChildEndPoint();
	}
	
	public function setPoolConditions()
	{
		$this->pool->setInitialChildren(4);
		$this->pool->setMaxOfTmpChildren(4);
		$this->pool->setChildProcess('ChildSender.php');
		$this->pool->createInitialChildren();
	}
}

?>
