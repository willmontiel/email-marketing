<?php
class ContactlistController extends ControllerBase
{
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;
		
		$idAccount=$this->user->account->idAccount;
		
		$query = $this->modelsManager->createQuery("SELECT Contactlist.* FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = $idAccount");
		$lists = $query->execute();
		
		$this->view->setVar("contactlists", $lists);
		
	}
	
	public function showAction($id)
	{
		$r = $this->verifyAcl('contactlist', 'show', '');
		if ($r)
			return $r;	
		
		$this->view->setVar();
		
	}
	
}


