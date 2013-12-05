<?php
class ContactListWrapper extends BaseWrapper
{


	/**
	 * Crea un arreglo con la informacion del objeto Contactlist
	 * para que pueda ser convertido a JSON
	 * @param Contactlist $contactlist
	 * @param Account $account
	 * @return array
	 */
	public static function convertListToJson(Contactlist $contactlist, Account $account = null)
	{
		$object = array();
		if ($account == null) {
			$account = $contactlist->dbase->account;
		}
		$object['id'] = intval($contactlist->idContactlist);
		$object['name'] = $contactlist->name;
		$object['description'] = $contactlist->description;
		$object['createdon'] = $contactlist->createdon;
		$object['updatedon'] = $contactlist->updatedon;
		$object['dbase'] = $contactlist->Dbase->idDbase;
		$object['infocontact'] = $account->idAccount;

		$object['totalContacts'] = $contactlist->Ctotal;
		$object['activeContacts'] = $contactlist->Cactive;
		$object['unsubscribedContacts'] = $contactlist->Cunsubscribed;
		$object['bouncedContacts'] = $contactlist->Cbounced;
		$object['spamContacts'] = $contactlist->Cspam;
		$object['inactiveContacts'] = $contactlist->getInactiveContacts();

		return $object;
	}
	
	public function validateListBelongsToAccount(Account $account, $idContactlist)
	{
		$listExist = Contactlist::countContactListsInAccount($account, 'idContactlist = :id:', array('id' => $idContactlist));

		if($listExist > 0) {
			return true;
		}
		else {
			$this->addFieldError('name', 'La lista de contactos no existe!');
			throw new InvalidArgumentException('No existe lista de contactos!');
			return false;
		}
		
	}


	public function createContactlist($contents)
	{
		
		if (!isset($contents->name) || trim($contents->name) == '' || $contents->name == null ) {
			$this->addFieldError('name', 'El nombre de la lista de contactos es requerido!');
			throw new InvalidArgumentException('El nombre de la lista de contactos es requerido!');
		}
		$cnt = Contactlist::countContactListsInAccount($this->account, 'Contactlist.name = :name:', array('name' => $contents->name));

		if($cnt != 0) {
			$this->addFieldError('name', 'Este nombre de lista ya existe en la base de datos seleccionada');
			throw new InvalidArgumentException('Nombre de lista de contacto duplicado');
		}
		
		$list = $this->createNewContactList($contents);

		return array('list' => self::convertListToJson($list, $list->dbase->account));
		
	}
	public function assignDataToContactList($contents, $list)
	{
		$list->idDbase = $contents->dbase;
		$list->name = $contents->name;
		$list->description = (isset($contents->description))?$contents->description:"Sin Descripcion";
		$list->createdon = $contents->createdon;
		$list->updatedon = $contents->updatedon;
	
	}
	
	public function findContactListInAccount($id, Account $account)
	{
		$list = Contactlist::findFirstByIdContactlist($id);
		
		if ($list && $list->dbase->account == $account) {
			$list = $this->convertListToJson($list, $account);
		}
		else {
			throw new InvalidArgumentException('Lista no existe en la cuenta');
		}
		
		return array('list' => $list, 
			 'dbase' => DbaseWrapper::getDbasesAsJSON($account),
			 'meta' => $this->pager->getPaginationObject()
		) ;
	}
	
	public function findContactListByAccount(Account $account, $name = null)
	{
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
				$lista[] = self::convertListToJson($contactlist, $account);
			}
		}

		// Actualizar el elemento de paginacion
		$this->pager->setRowsInCurrentPage(count($lista));
		$this->pager->setTotalRecords($total);
		
		return array('lists' => $lista, 
					 'dbase' => DbaseWrapper::getDbasesAsJSON($account),
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
		
		$contactList = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
		
		if (!$contactList) {
			throw new \InvalidArgumentException('Lista no encontrada en la base de datos!');
			$this->addFieldError('Lista', 'No se encuentra la lista en la base de datos');
		}
		
		else {
			$mensaje = $this->assignDataToContactList($contents, $contactList);
		
			if(!$contactList->save()){
				return array('lists' => 'errror');
			}
			else{
				return array('list' => self::convertListToJson($contactList, $contactList->dbase->account));
			}
		}
		
	}
	
	public function deleteContactList($idContactlist)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$list = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
		
		$idDbase = $list->idDbase;
		
		$query = 'DELETE CO
					FROM coxcl CL	
						JOIN contact CO ON CL.idContact = CO.idContact
					WHERE CL.idContactlist = ?
						AND NOT EXISTS 
							(SELECT C1.idContact
							FROM coxcl C1
							WHERE C1.idContact = CO.idContact
								AND idContactlist != ?)';
		
		$db->begin();
		$cascadeDelete = $db->execute($query, array($idContactlist, $idContactlist));
		$deleteContaclist = $db->execute('DELETE FROM contactlist WHERE idContactlist = ?', array($idContactlist));
		
		if ($cascadeDelete == false || $deleteContaclist == false) {
			$db->rollback();
			throw new \InvalidArgumentException('Ha ocurrido un error, contacta al administrador !');
		}
		
		$db->commit();		

		$dbase = Dbase::findFirstByIdDbase($idDbase);
		
		$dbase->updateCountersInDbase();		

		return $deleteContaclist;
	}

	public function findContactListByIdDbase(Account $account, $idDbase)
	{
		$contactLists = Contactlist::find(array(
			'conditions' => 'idDbase = ?1',
			'bind' => array(1 => $idDbase)
		));
		
		$lists = array();
		
		if ($contactLists) {
			$this->pager->setTotalRecords(count($contactLists));
			foreach ($contactLists as $list) {
				$lists[] = $this->convertListToJson($list, $account);
			}
		}
		return array(
					'lists' => $lists, 
					'dbase' => DbaseWrapper::getDbasesAsJSON($account),
					'meta' => $this->pager->getPaginationObject()
				);
	}
}


