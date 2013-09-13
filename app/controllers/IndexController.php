<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$account = $this->user->account;
		
		if ($account->accountingMode == 'Contacto') {
			$this->view->setVar('confAccount', $account);
			$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
		}
		
		$this->view->setVar('confAccount', $account);
    }
}