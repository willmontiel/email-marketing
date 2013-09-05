<?php
class ContactlistController extends ControllerBase
{
	
	public function indexAction()
	{
		
		$r = $this->verifyAcl('contactlist', 'index', '');
		if ($r)
			return $r;

		$idAccount = $this->user->account->idAccount;
		
		$db = Phalcon\DI::getDefault()->get('db');
		$sql = "SELECT customfield .* 
				FROM customfield
					JOIN dbase ON ( customfield.idDbase = dbase.idDbase ) 
				WHERE dbase.idAccount = :idAccount";
		
		 $results = $db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, array('idAccount' => $idAccount));
//		
//		$results =  Phalcon\DI::getDefault()->get('modelsManager')->executeQuery($sql, array ('idAccount' => $idAccount));
//		
//		foreach ($results as $result) {
//			$arrayFields[$result->idCustomField] = array(
//					'idCustom' => $result->idDbase,
//					'name' => $result->name, 
//					'type' => $result->type
//			);
//		}
		
		$totalFields = count($results);
		$this->view->setVar("totalFields", $totalFields);
		$this->view->setVar("fields", $results);
	}
	
	public function showAction($id)
	{
		$r = $this->verifyAcl('contactlist', 'show', '');
		if ($r)
			return $r;	
		
		$list = Contactlist::findFirstByIdContactlist($id);
		
		$this->view->setVar('datalist', $list);
		
		$fields = Customfield::findByIdDbase($list->idDbase);
		
		$this->view->setVar("fields", $fields);
		
	}
	
}


