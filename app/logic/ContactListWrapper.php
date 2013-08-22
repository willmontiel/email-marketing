<?php
class ContactListWrapper extends ControllerBase
{

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
	
	public function findContactListByAccount(Account $account, $limit, $page, $name = null)
	{
		$idAccount = $account->idAccount;
		$parameters = array('idAccount' => $idAccount);
		$nameFilter = ' AND Contactlist.name=:name:';
		
		$querytxt = "SELECT COUNT(*) AS cnt FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = :idAccount:";
		if ($name) {
			$querytxt .= $nameFilter;
			$parameters['name'] = $name;
		}
		$query2 = $this->modelsManager->createQuery($querytxt);
        $result = $query2->execute($parameters)->getFirst();
				
		$total = $result->cnt;
		$availablepages = ceil($total/$limit);
		
		$page = ($page<1)?1:$page;
		$start = ($page-1)*$limit;
		
		$lista = array();


		$querytxt2 = "SELECT Contactlist.* FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = :idAccount:";
		if ($name) {
			$querytxt2 .= $nameFilter;
		}
		if ($limit != 0) {
			$querytxt2 .= ' LIMIT ' . $limit . ' OFFSET ' . $start;
		}
        $query = $this->modelsManager->createQuery($querytxt2);
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
		return array('lists' => $lista, 
					 'dbases' => $bdjson,
					 'meta' => array( 'pagination' => array('page' => $page, 'limit' =>$limit, 'total' => $total,'availablepages' => $availablepages) ) 
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

	
}

