<?php
class ErrorController extends \Phalcon\Mvc\Controller
{
	public function indexAction()
	{
		
	}
	
	public function linkAction()
	{
		
	}
	
	public function notavailableAction()
	{
		$this->response->setStatusCode(503, 'System unavailable');
	}
	
	public function unauthorizedAction()
	{
		
	}
}