<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		try{
			$dashboard = new \EmailMarketing\General\Dashboard\DashboardSummary();
			$dashboard->setAccount($this->user->account);
		} 
		catch (\InvalidArgumentException $e) {
			$this->logger->log($e);
		}
		catch (\Exception $e) {
			$this->logger->log($e);
		}
		$this->view->setVar('stats', $dashboard);
		
		$this->view->setVar('confAccount', $this->user->account);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
    }
}