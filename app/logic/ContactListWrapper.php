<?php
class ContactListWrapper
{
	protected $pager;
	protected $_di;

	public function __construct()
	{
		$this->pager = new PaginationDecorator();
	}
	
	public function setPager(PaginationDecorator $p)
	{
		$this->pager = $p;
	}
	/**
	 * Crea un arreglo con la informacion del objeto Contactlist
	 * para que pueda ser convertido a JSON
	 * @param Contactlist $contactlist
	 * @return array
	 */
	protected function convertListToJson($contactlist)
	{
		$object = array();
		$object['id'] = $contactlist->idList;
		$object['name'] = $contactlist->name;
		$object['description'] = $contactlist->description;
		$object['createdon'] = $contactlist->createdon;
		$object['updatedon'] = $contactlist->updatedon;
		$object['dbase_id'] = $contactlist->Dbase->idDbase;

		return $object;
	}

	protected function convertBDToJson($dbase)
	{
		$object = array();
		$object['id'] = $dbase->idDbase;
		$object['name'] = $dbase->name;
		return $object;
	}
	
	public function validateListBelongsToAccount($idList)
	{
		$Dbases = $this->user->account->dbases;
		
		foreach ($Dbases as $Dbase) {
			$listExist = Contactlist::findFirst(array(
				"conditions" => "idDbase = ?1 AND idList = ?2",
				"bind"       => array(1 => $Dbase->idDbase, 2 => $idList)
			));
		}
		if(!$listExist) {
			return true;
		}
		else {
			return true;
		}
		
	}


	public function validateContactListData($contents)
	{
		
		$this->errortxt = array();
		$failed = false;
		if (!isset($contents->name)) {
			$this->errortxt[] = '"name" requerido';
			$failed = true;
		}
		
		else {
			$existName = Contactlist::findFirstByName($contents->name);

			if(!$existName) {
				$this->createNewContactList($contents);
			}
			else {
				return array('mensaje' => 'La lista ya existe',);
			}
		}
		
	}
	public function assignDataToContactList($contents, $list)
	{
		$list->idDbase = $contents->dbase_id;
		$list->name = $contents->name;
		$list->description = $contents->description;
		$list->createdon = $contents->createdon;
		$list->updatedon = $contents->updatedon;
	
	}
	
	public function findContactListByAccount(Account $account, $name = null)
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		$idAccount = $account->idAccount;
		$parameters = array('idAccount' => $idAccount);
		$nameFilter = ' AND Contactlist.name=:name:';
		
		$querytxt = "SELECT COUNT(*) AS cnt FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = :idAccount:";
		if ($name) {
			$querytxt .= $nameFilter;
			$parameters['name'] = $name;
		}
		$query2 = $modelManager->createQuery($querytxt);
        $result = $query2->execute($parameters)->getFirst();
				
		$total = $result->cnt;
		
		$lista = array();


		$querytxt2 = "SELECT Contactlist.* FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = :idAccount:";
		if ($name) {
			$querytxt2 .= $nameFilter;
		}
		if ($this->pager->getRowsPerPage() != 0) {
			$querytxt2 .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		}
        $query = $modelManager->createQuery($querytxt2);
		$contactlists = $query->execute($parameters);
		
		if ($contactlists) {
			foreach ($contactlists as $contactlist) {
				$lista[] = $this->convertListToJson($contactlist);
			}
		}
		$bdjson = array();
		foreach ($account->dbases as $bd) {
			$bdjson[] = $this->convertBDToJson($bd);
		}
		
		$this->pager->setRowsInCurrentPage(count($lista));
		$this->pager->setTotalRecords($total);
		return array('lists' => $lista, 
					 'dbases' => $bdjson,
					 'meta' => $this->pager->getPaginationObject()
				) ;
		
	}
	
	public function createNewContactList($contents)
	{
		
		$list = new Contactlist();
		$this->assignDataToContactList($contents, $list);
		

		if(!$list->save()){
			return array('lists' => 'errror');
		}
		else{

		}
	}
	
	public function updateContactList($contents, $idList)
	{
		
		$contactList = Contactlist::findFirstByIdList($idList);
		
		if (!$contactList) {
			throw new \InvalidArgumentException('Lista no encontrada en la base de datos!');
		}
		
		else {
			$this->assignDataToContactList($contents, $contactList);
		
			if(!$contactList->save()){
				return array('lists' => 'errror');
			}
			else{

			}
		}
		
	}
	
	public function deleteContactList($idList)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$query = 
			"SELECT C1.idContact, C1.idList ,COUNT(*) 
			FROM Coxcl C1
				JOIN (SELECT idContact FROM Coxcl WHERE idList = :idList) C2 ON (C1.idContact = C2.idContact) 
			GROUP BY 1 
			HAVING COUNT(*) = 1";

		$results = $db->fetchAll($query, Phalcon\Db::FETCH_ASSOC, array('idList' => $idList));
		
		$this->db->begin();
			$deleteList = $db->delete(
					'Contactlist',
					'idList = '.$idList  
			);
			if(!$deleteList) {
		$this->db->rollback();
			}
			else {
				foreach ($results as $result) {	
					$contact = $db->delete(
						'Contact',
						'idContact = '.$result["idContact"]
					);
		$this->db->commit();
				}
			}
			
	
	}

	
}


