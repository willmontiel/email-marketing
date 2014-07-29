<?php
class ContactListWrapper extends BaseWrapper
{

	public function setUser(User $user) {
		$this->user = $user;
	}

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
//		$cnt = Contactlist::countContactListsInAccount($this->account, 'Contactlist.name = :name:', array('name' => $contents->name));
		
		$contactlist = Contactlist::findFirst(array(
			'conditions' => 'name = ?1 AND idDbase = ?2',
			'bind' => array(1 => $contents->name,
							2 => $contents->dbase)
		));
		
		if($contactlist) {
			$this->addFieldError('name', 'Ya existe una lista de contactos con este mismo nombre en la base de datos, por favor verifique la información');
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
			$this->addFieldError('error', 'No se encuentra la lista en la base de datos');
		}
		else {
			$othercl = Contactlist::findFirst(array(
				'conditions' => 'name = ?1 AND idDbase = ?2',
				'bind' => array(1 => $contents->name, 
								2 => $contactList->idDbase)
			));
			
			if ($othercl) {
				$this->addFieldError('error', 'Ya existe una lista de contactos con este mismo nombre en la base de datos, por favor verifique la información');
				throw new \InvalidArgumentException('Nombre de lista de contacto duplicado');
			}
			
			$this->assignDataToContactList($contents, $contactList);
		
			if(!$contactList->save()){
				$this->addFieldError('error', 'Ha ocurrido un error, por favor contacte al administrador');
				throw new InvalidArgumentException('Error, while updating contactlist');
			}
			else{
				return array('list' => self::convertListToJson($contactList, $contactList->dbase->account));
			}
		}
		
	}
	
	public function deleteContactList($idContactlist, $override = FALSE)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		$list = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
		
		$idDbase = $list->idDbase;
		
		$query = "DELETE CO
					FROM coxcl CL	
						JOIN contact CO ON CL.idContact = CO.idContact
					WHERE CL.idContactlist = ?
					AND NOT EXISTS 
						(SELECT C1.idContact
						FROM coxcl C1
						WHERE C1.idContact = CO.idContact
							AND idContactlist != ?)";
		
		$query3  = "DELETE FROM contactlist WHERE idContactlist = ?";
		$query3Array = array($idContactlist);
		
		if(!$override) {
			$time = new \DateTime('-30 day');
			$time->setTime(0, 0, 0);
			$query.=" AND NOT EXISTS
						(SELECT MC.idContact
						FROM mxc MC
							JOIN mail MA ON MC.idMail = MA.idMail
						WHERE MC.idContact = CO.idContact
						AND MA.finishedon > {$time->getTimestamp()})";
						
			$query2 = "DELETE CL
					FROM coxcl CL	
						JOIN contact CO ON CL.idContact = CO.idContact
						JOIN coxcl C1 ON C1.idContact = CO.idContact
					WHERE CL.idContactlist = ?
					AND C1.idContactlist != ?
					AND NOT EXISTS
						(SELECT MC.idContact
						FROM mxc MC
							JOIN mail MA ON MC.idMail = MA.idMail
						WHERE MC.idContact = CO.idContact
						AND MA.startedon > {$time->getTimestamp()}
						AND ( MA.finishedon = 0 OR MA.finishedon > {$time->getTimestamp()}) )";
						
			$query3.= " AND ( SELECT COUNT(*) FROM coxcl WHERE idContactlist = ? ) < 1";
			array_push($query3Array, $idContactlist);
		}
		
		$db->begin();
		//1. Borramos las relaciones entre lista y contactos (coxcl y contaclist)
			//1.1 Borrar el contacto en caso de no tener asociaciones con otras listas y tampoco se le haya realizado un envio en los ultimos 30 dias
		$cascadeDelete = $db->execute($query, array($idContactlist, $idContactlist));
			//1.2 Borrar las relaciones con los contactos existentes en otras listas y que no hayan realizado un envio en los ultimos 30 dias
		$relationsDelete = (isset($query2)) ? $db->execute($query2, array($idContactlist, $idContactlist)): true;
			//2. Borramos la lista de contactos si no tiene contactos asociados
		$deleteContaclist = $db->execute($query3, $query3Array);
		
		if ($cascadeDelete == false || $deleteContaclist == false || $relationsDelete == false) {
			$db->rollback();
			throw new \Exception('Ha ocurrido un error, contacta al administrador !');
		}
		
		$db->commit();		
		
		$dbase = Dbase::findFirstByIdDbase($idDbase);
		
		//3. Se actualizan los contadores (Los contadores de lista se actualizan en caso de que quede al menos un contacto)
		$dbase->updateCountersInDbase();		
		
		$chklist = Contactlist::findFirst(array(
			"conditions" => "idContactlist = ?1",
			"bind" => array(1 => $idContactlist)
		));
		if( $chklist ) {
			$chklist->updateCountersInContactlist();
			throw new \Exception('La lista no se pudo eliminar ya que aun contiene contactos');
		}

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


