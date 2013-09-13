<?php

class IndexController extends ControllerBase
{
    public function indexAction()
    {
		$this->view->setVar('confAccount', $this->user->account);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
    }
}