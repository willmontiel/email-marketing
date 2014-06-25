<?php

class CampaignController extends ControllerBase
{
	public function indexAction()
	{
		
	}
	
	public function listAction()
	{
		
	}
	
	public function newAction($type)
	{
		$account = $this->user->account;
		
		$dbases = Dbase::findByIdAccount($account->idAccount);
		$this->view->setVar('dbases', $dbases);
		
		if ($this->request->isPost()) {
			$campaignWrapper = new CampaignWrapper();
			try {
				$idDbase;
			
				$campaignWrapper->setDbase($dbase);
			}
			catch (Exception $e) {
				$this->flashSession->error("Error: {$campaignWrapper->getFieldErrors[0]}");
				$this->logger->log("Exception: {$e}");
			}
		}	
	}
	
	public function editAction()
	{
		
	}
	
	public function deleteAction()
	{
		
	}
}