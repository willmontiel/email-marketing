<?php
class StatisticController extends ControllerBase
{
	public function indexAction()
	{

	}
	
	public function showAction($idMail)
	{
		$log = $this->logger;
		$log->log('El Id de Mail es: ' . $idMail);
	}
}