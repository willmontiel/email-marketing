<?php
class StatisticController extends ControllerBase
{
	public function indexAction()
	{

	}
	
	public function mailAction($idMail)
	{
		$log = $this->logger;
		$log->log('El Id de Mail es: ' . $idMail);
	}
	
	public function dbaseAction($idDbase)
	{
		$log = $this->logger;
		//$log->log('El Id de Base de Datos es: ' . $idDbase);
	}
}