<?php
class SendingprocessController extends ControllerBase
{
	public function getprocessesinfoAction()
	{
		$process = new ProcessStatus();
		$status = $process->getStatus();
		if ($status !== null) {
			return $this->setJsonResponse($status);
		}
		return null; 
	}
	
	public function indexAction()
	{	
		
	}
	
	public function pauseAction($idTask)
	{
		$process = new ProcessStatus();
		
		$process->pauseTask($idTask);
		
		return $this->response->redirect('sendingprocess');
	}
}	
