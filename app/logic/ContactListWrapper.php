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
	
	public function validateListBelongsToAccount(Account $account, $idList)
	{
		$bContactList = Contactlist::findFirst($idList);
		
		if (!$bContactList || $bContactList->dbase->account != $account) {
			throw new Exception('Error');
			return false;
		}
		
		else {
			return true;
		}
		
	}


	public function validateContactListData($contents)
	{
		
		if (!isset($contents->name)) {
			throw new InvalidArgumentException('No has enviado un nombre');
		}
		
		else {
			$existName = Contactlist::findFirstByName($contents->name);

			if(!$existName) {
				$this->createNewContactList($contents);
			}
			else {
				throw new InvalidArgumentException('Ya existe el nombre, por favor verifica la información');
			}
		}
		
	}
	public function assignDataToContactList($contents, $list)
	{
		$list->idDbase = $contents->dbase_id;
		$list->name = $contents->name;
		$list->description = $contents->description;
		$list->Ctotal = 0;
		$list->Cactive = 0;
		$list->Cunsubscribed = 0;
		$list->Cbounced = 0;
		$list->Cspam = 0;
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
		$db->delete(
				'Contactlist',
				'idList = '.$idList  
		);
		
		$ids = array_column($results, 'idContact');
		$deleteContacts = $db->delete(
			'Contact',
			'idContact IN (' . implode(',', $ids) . ')'
		);
		
		if(!$deleteContacts) {
			$this->db->rollback();
			return;
		}
		else {
			$this->db->commit();
		}
	}

	
}


