<?php
class ContactlistController extends ControllerBase
{
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;
		
		$this->view->setVar("contactlists", $this->list);
		$this->view->setVar("idbases", $this->contactlist->dbase);
		
	}
	
	public function showAction($id)
	{
		$r = $this->verifyAcl('contactlist', 'show', '');
		if ($r)
			return $r;	
		
		$this->view->setVar();
		
	}
	
}


