<?php
class SendingprocessController extends ControllerBase
{
	public function getprocessesinfoAction()
	{
		$communication = new Comunication();
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
		$communication = new Comunication();
		
		$communication->sendPausedToParent($idTask);
		
		return $this->response->redirect('sendingprocess');
	}
}	
