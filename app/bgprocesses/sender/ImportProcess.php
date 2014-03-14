<?php
class ImportProcess extends ProcessAbstract{

	public function getPublisherToChildrenSocket()
	{
		return SocketConstants::getImportPub2ChildrenEndPoint();
	}

	public function getReplyToClientSocket()
	{
		return SocketConstants::getImportRequestsEndPoint();
	}

	public function getPullFromChildSocket()
	{
		return SocketConstants::getImportPullFromChildEndPoint();
	}
	
	public function setPoolConditions()
	{
		$this->pool->setInitialChildren(2);
		$this->pool->setMaxOfTmpChildren(0);
		$this->pool->setChildProcess('childImport.php');
		$this->pool->createInitialChildren();
	}
}

?>
