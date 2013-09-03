<?php
class ContactListWrapper extends BaseWrapper
{


	/**
	 * Crea un arreglo con la informacion del objeto Contactlist
	 * para que pueda ser convertido a JSON
	 * @param Contactlist $contactlist
	 * @return array
	 */
	public static function convertListToJson(Contactlist $contactlist)
	{
		$object = array();
		$object['id'] = $contactlist->idContactlist;
		$object['name'] = $contactlist->name;
		$object['description'] = $contactlist->description;
		$object['createdon'] = $contactlist->createdon;
		$object['updatedon'] = $contactlist->updatedon;
		$object['dbase_id'] = $contactlist->Dbase->idDbase;

		$object['total_contacts'] = $contactlist->Ctotal;
		$object['active_contacts'] = $contactlist->Cactive;
		$object['unsubscribed_contacts'] = $contactlist->Cunsubscribed;
		$object['bounced_contacts'] = $contactlist->Cbounced;
		$object['spam_contacts'] = $contactlist->Cspam;
		$object['inactive_contacts'] = $contactlist->getInactiveContacts();

		return $object;
	}

	protected function convertBDToJson($dbase)
	{
		$object = array();
		$object['id'] = $dbase->idDbase;
		$object['name'] = $dbase->name;
		return $object;
	}
	
	public function validateListBelongsToAccount(Account $account, $idContactlist)
	{
//		$idAccount = $account->idAccount;
//		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
//		$contactlistExist= "SELECT COUNT(*) 
//							FROM contactlist
//								JOIN dbase
//								ON ( contactlist.idDbase = dbase.idDbase ) 
//							WHERE contactlist.idContactlist = :idContactlist:
//							AND dbase.idAccount = :idAccount:";
//				
//		$query = $modelManager->createQuery($contactlistExist);
//		$listExist = $query->execute(array(
//				"idContactlist" => "$idContactlist",
//				"idAccount" => "$idAccount"
//			)
//		);
		
		// Cambie el codigo de arriba con este codigo, que es utilizado en otras partes de forma similar
		$listExist = Contactlist::countContactListsInAccount($account, 'idContactlist = :id:', array('id' => $idContactlist));

		if($listExist > 0) {
			return true;
		}
		else {
			$this->addFieldError('name', 'La lista de contactos no existe!');
			throw new InvalidArgumentException('No exist lista de contactos!');
			return false;
		}
		
	}


	public function validateContactListData($contents)
	{
		
		if (!isset($contents->name) || trim($contents->name) == '' || $contents->name == null ) {
			$this->addFieldError('name', 'El nombre de la lista de contactos es requerido!');
			throw new InvalidArgumentException('El nombre de la lista de contactos es requerido!');
		}
		$cnt = Contactlist::countContactListsInAccount($this->account, 'Contactlist.name = :name:', array('name' => $contents->name));

		if($cnt != 0) {
			$this->addFieldError('name', 'Este nombre de lista ya existe en la base de datos seleccionada');
			$this->addFieldError('name', 'Elija un nombre diferente, o una base de datos diferente!');
			throw new InvalidArgumentException('Nombre de lista de contacto duplicado');
		}
		
		$list = $this->createNewContactList($contents);

		return array('list' => self::convertListToJson($list ));
		
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
//		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
//		$idAccount = $account->idAccount;
//		$parameters = array('idAccount' => $idAccount);
//		$nameFilter = ' AND Contactlist.name=:name:';
//		
//		$querytxt = "SELECT COUNT(*) AS cnt FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = :idAccount:";
//		if ($name) {
//			$querytxt .= $nameFilter;
//			$parameters['name'] = $name;
//		}
//		$query2 = $modelManager->createQuery($querytxt);
//        $result = $query2->execute($parameters)->getFirst();
//				
//		$total = $result->cnt;
//		
//		$lista = array();
//
//
//		$querytxt2 = "SELECT Contactlist.* FROM Contactlist JOIN Dbase ON Contactlist.idDbase = Dbase.idDbase WHERE idAccount = :idAccount:";
//		if ($name) {
//			$querytxt2 .= $nameFilter;
//		}
//		if ($this->pager->getRowsPerPage() != 0) {
//			$querytxt2 .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
//		}
//        $query = $modelManager->createQuery($querytxt2);
//		$contactlists = $query->execute($parameters);
//		
//		if ($contactlists) {
//			foreach ($contactlists as $contactlist) {
//				$lista[] = $this->convertListToJson($contactlist);
//			}
//		}
//		$bdjson = array();
//		foreach ($account->dbases as $bd) {
//			$bdjson[] = $this->convertBDToJson($bd);
//		}
//		
//		$this->pager->setRowsInCurrentPage(count($lista));
//		$this->pager->setTotalRecords($total);
//		return array('lists' => $lista, 
//					 'dbases' => $bdjson,
//					 'meta' => $this->pager->getPaginationObject()
//				) ;
		
		// Nuevo codigo
		$conditions = null;
		$parameters = null;
		$limits = null;
		if ($this->pager->getRowsPerPage() != 0) {
			$limits = array('number' => $this->pager->getRowsPerPage(), 'offset' => $this->pager->getStartIndex());
		}
		if ($name != null) {
			$conditions = 'Contactlist.name LIKE :name:';
			$parameters = array('name' => '%' . $name . '%');
		}
		// Contar las listas de contactos
		$total = Contactlist::countContactListsInAccount($account, $conditions, $parameters);
		// Consultarlas
		$contactlists = Contactlist::findContactListsInAccount($account, $conditions, $parameters, $limits);
		// Convertirlas a JSON
		$lista = array();
		if ($contactlists) {
			foreach ($contactlists as $contactlist) {
				$lista[] = self::convertListToJson($contactlist);
			}
		}
		// Incluir las bases de datos en la lista
		$bdjson = array();
		foreach ($account->dbases as $bd) {
			$bdjson[] = $this->convertBDToJson($bd);
		}
		// Actualizar el elemento de paginacion
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
			$txt = implode(PHP_EOL,  $list->getMessages());
			Phalcon\DI::getDefault()->get('logger')->log($txt);
			throw new Exception('Error al crear la lista de contactos!');
		}
		
		return $list;
	}
	
	public function updateContactList($contents, $idContactlist)
	{
		
		$contactList = Contactlist::findFirstByIdContactlist($idContactlist);
		
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
	
	public function deleteContactList($idContactlist)
	{
		$db = Phalcon\DI::getDefault()->get('db');

		
		$query = 'DELETE CO, CF
				  FROM coxcl CL	
					JOIN contact CO ON CL.idContact = CO.idContact 
					LEFT JOIN fieldinstance CF ON CO.idContact = CF.idContact
				  WHERE CO.idContact IN 
					(SELECT C1.idContact
					 FROM Coxcl C1
						JOIN (SELECT idContact FROM Coxcl WHERE idContactlist = ?) C2 ON (C1.idContact = C2.idContact) 
					 GROUP BY 1 
					 HAVING COUNT(*) = 1)';
		
		$db->begin();
		$db->execute($query, array($idContactlist));
		$db->execute('DELETE FROM Contactlist WHERE idContactlist = ?', array($idContactlist));
		$db->commit();
	}

	
}


