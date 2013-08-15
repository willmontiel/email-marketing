<?php
class ContactlistController extends ControllerBase
{
	
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;
		
		$idAccount = $this->user->account->idAccount;
		$this->view->setVar("account", $idAccount);
		
	}
	
	public function showAction($id)
	{
		$r = $this->verifyAcl('contactlist', 'show', '');
		if ($r)
			return $r;	
		
		$list = Contactlist::findFirstByIdList($id);
		
		$this->view->setVar('datalist', $list);
		
	}
	
}


