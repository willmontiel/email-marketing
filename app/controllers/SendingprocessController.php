<?php
class SendingprocessController extends ControllerBase
{
	public function getprocessesinfoAction()
	{
		$communication = new Communication();
		$status = $communication->getStatus();
		if ($status !== null) {
			return $this->setJsonResponse($status);
		}
		return null; 
	}
	
	public function indexAction()
	{	
		
	}
	
	public function stopAction($idTask)
	{
		$communication = new Communication();
		
		$communication->sendPausedToParent($idTask);
		
		return $this->response->redirect('sendingprocess');
	}
}	
