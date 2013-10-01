<?php
class ContactlistController extends ControllerBase
{
	
	public function indexAction()
	{
//		$idAccount = $this->user->account->idAccount;
//		
//		$db = Phalcon\DI::getDefault()->get('db');
//		$sql = "SELECT customfield .* 
//				FROM customfield
//					JOIN dbase ON ( customfield.idDbase = dbase.idDbase ) 
//				WHERE dbase.idAccount = :idAccount";
//		
//		$results = $db->fetchAll($sql, Phalcon\Db::FETCH_ASSOC, array('idAccount' => $idAccount));
//		
//		$totalFields = count($results);
//		
//		$arrayFields = array();
//		
//		foreach($results as $key){
//			$k = $key['idDbase'];
//			$idCustomField = $key['idCustomField'];
//			$name = $key['name'];
//			$type = $key['type'];
//			
//			if(!isset($arrayFields[$k])){
//				$arrayFields[$k];
//			}
//			$arrayFields[$k][] = $idCustomField;
//			$arrayFields[$k][] = $name;
//			$arrayFields[$k][] = $type;
//		}
//		
//		$this->view->setVar("totalFields", $totalFields);
//		$this->view->setVar("fields", $arrayFields);
	}
	
	public function showAction($id)
	{
	
		$list = Contactlist::findFirstByIdContactlist($id);
		
		$this->view->setVar('datalist', $list);
		
		$fields = Customfield::findByIdDbase($list->idDbase);
		
		$this->view->setVar("fields", $fields);
		
	}
	
}


