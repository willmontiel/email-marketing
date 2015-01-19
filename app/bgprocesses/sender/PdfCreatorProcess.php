<?php
class PdfCreatorProcess extends ProcessAbstract
{
	public function getPublisherToChildrenSocket()
	{
		return SocketConstants::getPdfCreatorPub2ChildrenEndPoint();
	}

	public function getReplyToClientSocket()
	{
		return SocketConstants::getPdfCreatorRequestsEndPoint();
	}

	public function getPullFromChildSocket()
	{
		return SocketConstants::getPdfCreatorPullFromChildEndPoint();
	}
	
	public function setPoolConditions()
	{
		$this->pool->setInitialChildren(2);
		$this->pool->setMaxOfTmpChildren(0);
		$this->pool->setChildProcess('ChildPdfCreator.php');
		$this->pool->createInitialChildren();
	}
}