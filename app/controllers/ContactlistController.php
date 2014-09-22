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
		$contactlist = Contactlist::findFirst(array(
			'conditions' => 'idContactlist = ?1',
			'bind' => array(1 => $id)
		));
		
		if ($contactlist) {
			$fields = Customfield::findByIdDbase($contactlist->idDbase);
			$statWrapper = new StatisticsWrapper();
			$statWrapper->setContactlist($contactlist);
			$statWrapper->groupContactsByDomainsAndContactlist();
			$statWrapper->regroupDomains();
			$domains = $statWrapper->getDomains();

			$this->view->setVar('datalist', $contactlist);
			$this->view->setVar("fields", $fields);
			$this->view->setVar("domains", $domains);
		}
		else {
			return $this->response->redirect('error');
		}
	}
	
}


