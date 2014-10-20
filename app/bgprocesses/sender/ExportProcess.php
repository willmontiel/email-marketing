<?php
class ImportProcess extends ProcessAbstract
{
	public function getPublisherToChildrenSocket()
	{
		return SocketConstants::getExportPub2ChildrenEndPoint();
	}

	public function getReplyToClientSocket()
	{
		return SocketConstants::getExportRequestsEndPoint();
	}

	public function getPullFromChildSocket()
	{
		return SocketConstants::getExportPullFromChildEndPoint();
	}
	
	public function setPoolConditions()
	{
		$this->pool->setInitialChildren(2);
		$this->pool->setMaxOfTmpChildren(0);
		$this->pool->setChildProcess('ChildImport.php');
		$this->pool->createInitialChildren();
	}
}