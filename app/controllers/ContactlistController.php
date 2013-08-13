<?php
class ContactlistController extends ControllerBase
{
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;
		
		$this->view->setVar("contactlists", $this->contactlist->dbase);
	}
	
	public function showAction()
	{
		$r = $this->verifyAcl('contactlist', 'show', '');
		if ($r)
			return $r;
	    $users = User::find("idAccount = $id");
		
		$this->view->setVar("allUser", $users);	   
		
	}
	
}


