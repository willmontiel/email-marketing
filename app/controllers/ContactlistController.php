<?php
class ContactlistController extends ControllerBase
{
	
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;
	}
	
	public function showAction($id)
	{
		$r = $this->verifyAcl('contactlist', 'show', '');
		if ($r)
			return $r;	
		
		$list = Contactlist::findFirstByIdList($id);
		
		$this->view->setVar('datalist', $list);
		
		$fields = Customfield::findByIdDbase($list->idDbase);
		
		$this->view->setVar("fields", $fields);
		
	}
	
}


