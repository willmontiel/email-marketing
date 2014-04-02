<?php


/**
 * Description of ContactWrapper
 *
 * @author hectorlasso
 */
class ContactWrapper extends BaseWrapper
{

	protected $idDbase;
	protected $idContactlist;
	protected $account;
	protected $contact;
	protected $ipaddress;
	protected $db;
	protected $pager;
	protected $mailHistory;

    protected $_di;
	protected $counter;


	const PAGE_DEFAULT = 5;
	const DEFAULT_LIMIT_SEARCH = 200;

	public function __construct()
	{
		parent::__construct();
		$this->counter = new ContactCounter();
		$this->db = Phalcon\DI::getDefault()->get('db');
		
	}
	
	public function setContactMailHistory(\EmailMarketing\General\ModelAccess\ContactMailHistory $mailhistory)
	{
		$this->mailHistory = $mailhistory;
	}
	
	public function startTransaction()
	{
		$this->db->begin();
	}
	
	public function endTransaction($cont = true)
	{
		$this->db->commit();
		$this->counter->saveCounters();
		
		if($cont) {
			$this->db->begin();
			$this->counter = new ContactCounter();
		}
	}
	
	public function rollbackTransaction()
	{
		$this->db->rollback();
	}

	public function setIdDbase($idDbase)
	{
		$this->idDbase = $idDbase;
	}
	
	public function setIdContactlist($idContactlist)
	{
		$this->idContactlist =$idContactlist;
	}

	public function setIPAdress($ipaddress) {
		$this->ipaddress = ip2long($ipaddress);
	}

	public function updateContact($idEmail, $updates, $transaction = null)
	{
		$contacts = Contact::find(array(
			"conditions" => "idEmail = ?1",
			"bind" => array(1 => $idEmail)
			)
		);
				
		foreach ($contacts as $contact) 
		{
			$oldContact = Contact::findFirstByIdContact($contact->idContact);
			
			if (!$contact) {
				throw new \InvalidArgumentException('Contacto no se ha encontrado en la base de datos!');
			}
			
			// Si se recibe una transaccion, asignarla al contacto que se actualiza
			if ($transaction) {
				$contact->setTransaction($transaction);
			}
			foreach ($updates as $key=>$value) {
				$contact->$key = $value;
			}

			if (!$contact->save()) {
				$errmsg = $contact->getMessages();
				$msg = '';
				foreach ($errmsg as $err) {
					$msg .= $err . PHP_EOL;
				}
				throw new \Exception('Error al actualizar el contacto: >>' . $msg . '<<');
			} else {
				Phalcon\DI::getDefault()->get('logger')->log("El estado del viejo contacto: " . $oldContact->unsubscribed . " y el del nuevo es: " . $contact->unsubscribed);
				$this->counter->updateContact($oldContact, $contact);
			}
		}
		$this->counter->saveCounters();
	}

	public function updateContactFromJsonData($idContact, $data)
	{
		// Actualizar contacto:
		
		// 1) Cargar el contacto
		$contact = Contact::findFirstByIdContact($idContact);
		if (!$contact) {
			throw new \InvalidArgumentException('Contacto no encontrado en la base de datos!');
		}
		// 2) Validar que pertenezca a la BD
		if ($contact->dbase->idDbase != $this->idDbase) {
			throw new \InvalidArgumentException('Contacto no encontrado en la base de datos!');
		}
		// 3) Verificar si cambia el email

		if ($data->email != $contact->email->email) {
			// 4) Si cambia => validar si existe o no dentro de la BD
			$email = $this->findEmailNotRepeated($data->email, $contact);
			
			// 5) Crear el email si es necesario
			if (!$email) {
				$email = $this->createEmail($data->email);
			}
			Phalcon\DI::getDefault()->get('logger')->log("Se crea email [id: {$email->idEmail}, email: {$email->email}]");
			Phalcon\DI::getDefault()->get('logger')->log("Email anterior {$contact->idEmail}");
			// Asignar el nuevo email
			
			
			$contact->idEmail = $email->idEmail;
			
			Phalcon\DI::getDefault()->get('logger')->log("Email nuevo antes grabar {$contact->idEmail}");
			
			if (!$contact->save()) {
				foreach ($contact->getMessages() as $msg) {
					throw new \Exception('Error al actualizar el email del contacto: >>' . $msg . '<<');
				}
			}
			
			Phalcon\DI::getDefault()->get('logger')->log("Email nuevo despúes de grabar {$contact->idEmail}");
		}

		$this->contact = $contact;
		// 0) Cargar el contacto Antigua para comparaciones 
		$oldContact = Contact::findFirstByIdContact($idContact);
		
		// 6) Actualizar los otros campos
		$this->assignDataToContact($this->contact, $data);
		

		// 7) Grabar cambios
		if (!$this->contact->save()) {
			$errmsg = $this->contact->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al actualizar el contacto: >>' . $msg . '<<');
		} else {
			$this->assignDataToCustomField($data);
			
			$this->counter->updateContact($oldContact, $this->contact);
			
			$this->counter->saveCounters();
			
			$swrapper = new SegmentWrapper;
			
			$swrapper->contactCreatedOrUpdated($this->contact);
		}			
		
		return $this->contact;
	}
	
	public function deleteContactFromList($contact, $list) 
	{
		$this->checkSendingStatusContact($contact);
		$association = Coxcl::findFirst("idContactlist = '$list->idContactlist' AND idContact = '$contact->idContact'");
		
		if($association->delete()) {
			$this->counter->deleteContactFromList($contact, $list);
		}		
		if (!Coxcl::findFirst("idContact = '$contact->idContact'")) {
			$customfields = Fieldinstance::findByIdContact($contact->idContact);
				foreach ($customfields as $field) {
					$field->delete();
				}

			if($contact->delete()) {
				$this->counter->deleteContactFromDbase($contact);
			}
		}
		$this->counter->saveCounters();
	}
	
	public function deleteContactFromDB($contact, $db)
	{
		$this->checkSendingStatusContact($contact);
		$allLists = Contactlist::findByIdDbase($db->idDbase);
		if (count($allLists) > 0) {
			try {
				Phalcon\DI::getDefault()->get('db')->begin();
				foreach ($allLists as $list) {
					$association = Coxcl::findFirst("idContactlist = '$list->idContactlist' AND idContact = '$contact->idContact'");
					if($association){
						if (!$association->delete()) {
							foreach ($association->getMessages() as $msg) {
								Phalcon\DI::getDefault()->get('logger')->log('Error while deleting assoc: ' . $msg);
								throw new Exception('Error while deleting assoc between contact and list!');
							}
						}
						$this->counter->deleteContactFromList($contact, $list);
					}
				}

				$customfields = Fieldinstance::findByIdContact($contact->idContact);
				if (count($customfields) > 0) {
					foreach ($customfields as $field){
						if (!$field->delete()) {
							foreach ($association->getMessages() as $msg) {
								Phalcon\DI::getDefault()->get('logger')->log('Error while deleting assoc: ' . $msg);
								throw new Exception('Error while deleting assoc between contact and customfields!');
							}
						}
					}
				}
				if (!$contact->delete()) {
					foreach ($contact->getMessages() as $msg) {
						Phalcon\DI::getDefault()->get('logger')->log('Error while deleting contact: ' . $msg);
						throw new Exception('Error while deleting contact');
					}
				}
				else {
					$this->counter->deleteContactFromDbase($contact);
					$this->counter->saveCounters();
				}
				Phalcon\DI::getDefault()->get('db')->commit();
				Phalcon\DI::getDefault()->get('logger')->log('Se borró contacto exitosamente');
				$response = new stdClass();
				$response->status = $contact;
				$response->type = 202;
				$response->msg = 'contact deleted success';
				
				return $response;
			}
			catch(Exception $e) {
				Phalcon\DI::getDefault()->get('logger')->log('Exception: ' . $e);
				Phalcon\DI::getDefault()->get('db')->rollback();
				$response = new stdClass();
				$response->status = 'failed';
				$response->type = 500;
				$response->msg = 'Error while deleting contact';
				
				return $response;
			}
			catch (InvalidArgumentException $e) {
				Phalcon\DI::getDefault()->get('logger')->log('Exception: ' . $e);
				Phalcon\DI::getDefault()->get('db')->rollback();
				$response = new stdClass();
				$response->status = 'failed';
				$response->type = 500;
				$response->msg = 'Error while deleting contact';
				
				return $response;
			}
		}
	}
	
	protected function checkSendingStatusContact(Contact $contact)
	{
		$time = new \DateTime('-30 day');
		$time->setTime(0, 0, 0);
		$modelManager = \Phalcon\DI::getDefault()->get('modelsManager');
		$sql = "SELECT *
				FROM Mxc AS x 
					JOIN Mail AS m ON (x.idMail = m.idMail)
				WHERE x.idContact = {$contact->idContact}
				AND m.finishedon > {$time->getTimestamp()}";
		$query = $modelManager->createQuery($sql);
		$result = $query->execute();
		if( count($result) > 0) {
			throw new \Exception('El contacto no puede ser eliminado');
		}
	}

	public function addExistingContactToListFromDbase($email, Contactlist $list, $saveCounters = true)
	{
		$idAccount = $this->account->idAccount;
		
		$existEmail = Email::findFirst("email = '$email' AND idAccount = $idAccount");
			
		if ($existEmail && !$this->findEmailBlocked($existEmail)) {

			$existContact = Contact::findFirst(array(
				"conditions" => "idEmail = ?1 and idDbase = ?2",
				"bind" => array(1 => $existEmail->idEmail,
								2 => $this->idDbase)
			));		
			
			if($existContact){
				
				$existList = Coxcl::findFirst("idContact = $existContact->idContact AND idContactlist = $list->idContactlist");
				
				if (!$existList){

					$this->associateContactToList($list, $existContact);
					
					if($saveCounters) {						
						$this->counter->saveCounters();
					}

					return $existContact;
				}
			}
		}
		return false;
	}
	
	public function createNewContactFromJsonData($data, Contactlist $list = null, $saveCounters = true)
	{
		//Validar que al crear un email no exceda el limite de emails en la cuenta
		if ($this->account->accountingMode == 'Contacto' && $this->account->countActiveContactsInAccount() >= $this->account->contactLimit) {
			$this->addFieldError('email', 'Ha sobrepasado el limite de contactos: [' . $this->account->contactLimit .  ']');
			throw new \InvalidArgumentException('Ha sobrepasado el limite de contactos: [' . $this->account->contactLimit .  ']', 3);
		}
		else {
			// Verificar existencia del correo en la cuenta actual
			$email = $this->findEmailNotRepeated($data->email);
			if ($email && !$this->findEmailBlocked($email)) {
				$this->addContact($data, $email, $list);
			}
			else {
				$this->addContact($data, null, $list);
			}
			if($saveCounters)
				$this->counter->saveCounters();
			
			$swrapper = new SegmentWrapper;
			
			$swrapper->contactCreatedOrUpdated($this->contact);
			
			return $this->contact;
		}
		
	}
	
	protected function findEmailNotRepeated($emailaddress, $curcontact = null)
	{
		// Verificar existencia del correo en la cuenta actual
		$email = Email::findFirst(
				array(
					'conditions' => 'idAccount = ?1 AND email = ?2',
					'bind' => array(1 => $this->account->idAccount, 2 => $emailaddress)
				)
		);
		// Existe el correo en la cuenta actual
		if ($email) {
			// Validar que no exista ya un contacto con ese correo dentro de la base de datos
			$contact = Contact::findFirst(
				array(
					'conditions' => 'idDbase = ?1 AND idEmail = ?2',
					'bind' => array(1 => $this->idDbase, 2 => $email->idEmail)
				)
			);
			// Forma simplificada de hacer validacion, por claridad se cambia
//			if ($contact && (!$curcontact || $contact->idContact != $curcontact->idContact) ) {
			if ($contact) {
				if  (!($curcontact && $contact->idContact == $curcontact->idContact)) {
					$this->addFieldError('email', 'Ya existe un contacto con ese email!');
					throw new \InvalidArgumentException('El correo electronico [' . $emailaddress .  '] ya existe en la base de datos', 0);
				}
			}
		}
		// Retornar el email
		return $email;
	}
	
	protected function findEmailBlocked($email)
	{
		if($email->blocked != 0) {
			$this->addFieldError('email', 'El correo electronico esta bloqueado por [' . $email->blockedemail->blockedReason . ']');
			throw new \InvalidArgumentException('El correo electronico [' . $email->email .  '] se encuentra bloqueado!', 2);
		} else {
			return false;
		}
	}

	protected function addContact($data, Email $email = null, Contactlist $list = null)
	{
		if ($email == null) {
			$email = $this->createEmail($data->email);
		}
		
		$this->contact = new Contact();

		$this->contact->email = $email;
		$this->contact->idDbase = $this->idDbase;
		
		$this->assignDataToContact($this->contact, $data, true);
		
		if (!$this->contact->save()) {
			$errmsg = $this->contact->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al crear el contacto: >>' . $msg . '<<');
		} 
		else {
			$this->counter->newContactToDbase($this->contact);
			
			if ($list == null) {
				$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			}
			$this->associateContactToList($list, $this->contact);
			
			$this->assignDataToCustomField($data);
			
		}
	}
	
	protected function assignDataToContact($contact, $data, $isnew = false)
	{
		$hora = time();
		
		$contact->name = $data->name;
		$contact->lastName = $data->lastName;

		if ($isnew) {
			$contact->ipSubscribed = $this->ipaddress;
			$contact->ipActivated = 0;
			$contact->unsubscribed = (!$data->isSubscribed)?$hora:0;
			$contact->subscribedon = ($data->isSubscribed)?$hora:0;
			$contact->ipSubscribed = $this->ipaddress;
			$contact->status = ($data->isActive)?$hora:0;
			$contact->ipActivated = ($data->isActive)?$this->ipaddress:0;
			$contact->email->bounced = ($data->isBounced)?$hora:0;
			$contact->email->spam = ($data->isSpam)?$hora:0;
		}
		else {
			if ($contact->unsubscribed != 0 && $data->isSubscribed) {
				if ($contact->email->blocked != 0) {
					// Email bloqueado!!!
					$this->addFieldError('email', 'El email esta bloqueado');
					throw new InvalidArgumentException('Email bloqueado!!!');
				}
				// Actualmente des-suscrito y se actualiza a suscrito
				$contact->unsubscribed = 0;
				$contact->subscribedon = $hora;
				$contact->ipSubscribed = $this->ipaddress;
			}
			else if ($contact->unsubscribed == 0 && !$data->isSubscribed) {
				// Actualmente suscrito, y se actualiza a des-suscrito
				$contact->unsubscribed = $hora;
			}

			if ($contact->status != 0 && !$data->isActive) {
				// Actualmente activo, y se desactiva
				$contact->status = 0;
			}
			else if ($contact->status == 0 && $data->isActive) {
				// Actualmente desactivado y se activa
				$contact->status = $hora;
				$contact->ipActivated = $this->ipaddress;
			}
			
			if ($contact->email->bounced != 0 && !$data->isBounced) {
				// Actualmente rebotado y se actualiza a no rebotado
				$contact->email->bounced = 0;
			}
			else if ($contact->email->bounced == 0 && $data->isBounced) {
				// Actualmente no rebotado, y se actualiza a rebotado
				$contact->email->bounced = $hora;
			}
			
			if ($contact->email->spam != 0 && !$data->isSpam) {
				// Actualmente spam y se actualiza a no spam
				$contact->email->spam = 0;
			}
			else if ($contact->spam == 0 && $data->isSpam) {
				// Actualmente no spam, y se actualiza a spam
				$contact->email->spam = $hora;
			}
		}
	}
	
	protected function assignDataToCustomField ($data)
	{
		$fields = Customfield::findByIdDbase($this->idDbase);
		
		foreach ($fields as $field) {
			$fieldinstance = new Fieldinstance();
			$fieldinstance->idCustomField = $field->idCustomField;
			$fieldinstance->idContact = $this->contact->idContact;
			$name = "campo".$field->idCustomField;
			$value = null;
			if ($field->type == "Date") {
				if($data->$name != null){
					$value = strtotime($data->$name);
				} 
				else { 
					$value = $field->defaultValue;
				}
				$fieldinstance->numberValue = $value;
			} else {
				if($data->$name != null){
					$value = $data->$name;
				} 
				else { 
					$value = $field->defaultValue;
				}
				$fieldinstance->textValue = $value;
			}
			if(!$fieldinstance->save()) {
				throw new \Exception('Error al crear los Campos Personalizados del Contacto');
			}
		}
	}
	
	protected function associateContactToList(Contactlist $list, Contact $contact)
	{
		$associate = new Coxcl();
				
		$associate->idContactlist = $list->idContactlist;
		$associate->idContact = $contact->idContact;
		
		if(!$associate->save())	{
			$m = $associate->getMessages();
			$a = array();
			foreach ($m as $msg) {
				$a[] = $msg;
			}
			$txt = implode(',', $msg);
			throw new \Exception('Error al asociar el contacto a la lista con idContactlist: '.$list->idContactlist. ' y idContact: ' .$contact->idContact. '!' . PHP_EOL . '[' . $txt . ']');
		} else {
			
			$this->counter->newContactToList($contact, $list);
		}
	}

	public function createEmail($mail)
	{
		$email = strtolower($mail);
		if (!\filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException('La direccion [' . $email . '] no es una direccion de correo valida!', 1);
		}
		
		$emailex = Email::findFirstByEmail($email);
		if ($emailex) {
			$contactex = Contact::findFirstByIdEmail($emailex->idEmail);
			if ($contactex) {
				if ($contactex->idDbase == $this->idDbase) {
					throw new InvalidArgumentException('La direccion ' . $email . ' ya existe en esta base de datos!');
				}
			}
		}
		
		$emailo = new Email();
		list($user, $edomain) = preg_split("/@/", $email, 2);
		$domain = Domain::findFirstByName($edomain);
		if (!$domain) {
			$domain = new Domain();
			$domain->name = $edomain;
			if (!$domain->save()) {
				$errmsg = $domain->getMessages();
				$msg = '';
				foreach ($errmsg as $err) {
					$msg .= $err . PHP_EOL;
				}
				throw new \Exception('Error al crear el dominio [' . $edomain . ']: >>' . $msg . '<<');
			}
		}
		
		$emailo->domain = $domain;
		$emailo->email = $email;
		$emailo->idDbase = $this->idDbase;
		$emailo->account = $this->account;
		$emailo->bounced = 0;
		$emailo->unsubscribed = 0;
		$emailo->spam = 0;
		$emailo->blocked = 0;

		if (!$emailo->save()) {
			$errmsg = $emailo->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al crear el email [' . $email . ']: >>' . $msg . '<<');
		}

		return $emailo;
	}

	
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}
	
	public function findContacts(Dbase $db)
	{
		// Buscar los contactos
		$idDbase = $db->idDbase;
		$parameters = array('idDbase' => $idDbase);
		
		$querytxt = "SELECT COUNT(*) AS cnt FROM Contact WHERE idDbase = :idDbase:";

		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$query2 = $modelManager->createQuery($querytxt);
        $result = $query2->execute($parameters)->getFirst();
				
		$total = $result->cnt;

		$querytxt2 = "SELECT Contact.* FROM Contact WHERE idDbase = :idDbase:";

		if ($this->pager->getRowsPerPage() != 0) {
			$querytxt2 .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		}
        $query = $modelManager->createQuery($querytxt2);
		$contacts = $query->execute($parameters);

		$contactos = array();
		
		if($contacts){
			foreach ($contacts as $contact) {
				$contactos[] = $this->convertContactToJson($contact);
			}
		}

		$this->pager->setRowsInCurrentPage(count($contactos));
		$this->pager->setTotalRecords($total);
		
		$listjson = array();
		
		foreach ($db->contactlist as $contactlist) {
			$listjson[] = $this->convertContactListToJson($contactlist);
		}
		
		return array('contacts' => $contactos,
					 'lists' => $listjson,
					 'meta' => $this->pager->getPaginationObject());
	}
	
	public function convertContactListToJson($contactlist)
	{
		$object = array();
		$object['id'] = $contactlist->idContactlist;
		$object['name'] = $contactlist->name;
		return $object;
	}

	public function convertContactToJson(Contact $contact)
	{
		$object = array();
		$object['id'] = $contact->idContact;
		$object['email'] = $contact->email->email;
		$object['name'] = $contact->name;
		$object['lastName'] = $contact->lastName;
		$object['isActive'] = ($contact->status != 0);
		$object['activatedOn'] = (($contact->status != 0)?date('d/m/Y H:i', $contact->status):'');
		$object['isSubscribed'] = ($contact->unsubscribed == 0);
		$object['subscribedOn'] = (($contact->subscribedon != 0)?date('d/m/Y H:i', $contact->subscribedon):'');
		$object['unsubscribedOn'] = (($contact->unsubscribed != 0)?date('d/m/Y H:i', $contact->unsubscribed):'');
		$object['isBounced'] = ($contact->email->bounced != 0);
		$object['bouncedOn'] = (($contact->email->bounced != 0)?date('d/m/Y H:i', $contact->email->bounced):'');
		$object['isSpam'] = ($contact->email->spam != 0);
		$object['spamOn'] = (($contact->email->spam != 0)?date('d/m/Y H:i', $contact->email->spam):'');
		$object['createdOn'] = (($contact->createdon != 0)?date('d/m/Y H:i', $contact->createdon):'');
		$object['updatedOn'] = (($contact->updatedon != 0)?date('d/m/Y H:i', $contact->updatedon):'');
		
		$object['ipSubscribed'] = (($contact->ipSubscribed)?long2ip($contact->ipSubscribed):'');
		$object['ipActivated'] = (($contact->ipActivated)?long2ip($contact->ipActivated):'');
		
		$object['isEmailBlocked'] = ($contact->email->blocked != 0);
		
		$this->mailHistory->findMailHistory($contact->idContact);
		$object['mailHistory'] = $this->mailHistory->getMailHistory();
		$customfields = Customfield::findByIdDbase($this->idDbase);

		// Consultar la lista de campos personalizados para esos contactos
		$finstancesO = Fieldinstance::findInstancesForMultipleContacts(array($contact->idContact));
		$fieldinstances = $this->createFieldInstanceMap($finstancesO);
		
		foreach ($customfields as $field) {
			$key = $contact->idContact . ':' . $field->idCustomField;
			$value = '';
			if (isset($fieldinstances[$key])) {
				$fvalue = $fieldinstances[$key];
				switch ($field->type) {
					case 'Date':
						if($fvalue['numberValue']) {
							$value = date('Y-m-d',$fvalue['numberValue']);
						} else {
							$value = "";
						}
						break;
					case 'Number':
						$value = $fvalue['numberValue'];
						break;
					default:
						$value = $fvalue['textValue'];
				}
			}
			$object['campo' . $field->idCustomField] = $value;
		}
		
		return $object;
	}
	
	protected function getMailHistory(Contact $contact)
	{
		$mailh = array();
		$query = "SELECT m.name, c.opening, c.clicks, c.bounced, c.spam, c.unsubscribe
					FROM mxc AS c JOIN mail AS m ON (c.idMail = m.idMail)
					WHERE c.idContact = {$contact->idContact}
					ORDER BY m.createdon";
		$r = $this->db->query($query);
		$alldata = $r->fetchAll();
		$count = count($alldata);
		if($count > 0) {
			foreach ($alldata as $a) {
				$mailh[] = array(
					'name' => $a['name'],
					'opening' => ($a['opening'] != 0) ? date('d-m-Y', $a['opening']) : NULL,
					'clicks' => ($a['clicks'] != 0) ? date('d-m-Y', $a['clicks']) : NULL,
					'bounced' => ($a['bounced'] != 0) ? date('d-m-Y', $a['bounced']) : NULL,
					'spam' => ($a['spam'] != 0) ? date('d-m-Y', $a['spam']) : NULL,
					'unsubscribe' => ($a['unsubscribe'] != 0) ? date('d-m-Y', $a['unsubscribe']) : NULL,
				);
			}
		}
		return json_encode($mailh);
	}

	/**
	 * 
	 * @param $contactRow
	 * @param array $customfields
	 * @param array $fieldinstances
	 * @return array
	 */
	public function convertCompleteContactToJson($contactRow, $customfields, $fieldinstances)
	{
		$contact = $contactRow->contact;
		$email   = $contactRow->email;
		$object = array();
		$object['id'] = $contact->idContact;
		$object['email'] = $email->email;
		$object['name'] = $contact->name;
		$object['lastName'] = $contact->lastName;
		$object['isActive'] = ($contact->status != 0);
		$object['activatedOn'] = (($contact->status != 0)?date('d/m/Y H:i', $contact->status):'');
		$object['isSubscribed'] = ($contact->unsubscribed == 0);
		$object['subscribedOn'] = (($contact->subscribedon != 0)?date('d/m/Y H:i', $contact->subscribedon):'');
		$object['unsubscribedOn'] = (($contact->unsubscribed != 0)?date('d/m/Y H:i', $contact->unsubscribed):'');
		$object['isBounced'] = ($email->bounced != 0);
		$object['bouncedOn'] = (($email->bounced != 0)?date('d/m/Y H:i', $email->bounced):'');
		$object['isSpam'] = ($email->spam != 0);
		$object['spamOn'] = (($email->spam != 0)?date('d/m/Y H:i', $email->spam):'');
		$object['createdOn'] = (($contact->createdon != 0)?date('d/m/Y H:i', $contact->createdon):'');
		$object['updatedOn'] = (($contact->updatedon != 0)?date('d/m/Y H:i', $contact->updatedon):'');
		
		$object['ipSubscribed'] = (($contact->ipSubscribed)?long2ip($contact->ipSubscribed):'');
		$object['ipActivated'] = (($contact->ipActivated)?long2ip($contact->ipActivated):'');
		
		$object['isEmailBlocked'] = ($email->blocked != 0);
		
		$this->mailHistory->findMailHistory($contact->idContact);
		$object['mailHistory'] = $this->mailHistory->getMailHistory();
		
		foreach ($customfields as $field) {
			$key = $contact->idContact . ':' . $field['id'];
			$value = '';
			if (isset($fieldinstances[$key])) {
				$fvalue = $fieldinstances[$key];
				switch ($field['type']) {
					case 'Date':
						if($fvalue['numberValue']) {
							$value = date('Y-m-d',$fvalue['numberValue']);
						} else {
							$value = "";
						}
						break;
					case 'Number':
						$value = $fvalue['numberValue'];
						break;
					default:
						$value = $fvalue['textValue'];
				}
			}
			$object[$field['name']] = $value;
		}
		
		return $object;
	}
	
	public function findContactsComplete(Contactlist $list)
	{
		// Arreglo de contactos encontrados
		$result = array();
		
		// Total de contactos
		$this->pager->setTotalRecords(Contact::countContactsInList($list));
		
		// Contactos
		$contacts = Contact::findContactsAndEmailsInList($list, null, null, array('number' => $this->pager->getRowsPerPage(), 'offset' => $this->pager->getStartIndex()));
		
		// IDs de contactos
		$ids = array();
		foreach ($contacts as $c) {
			$ids[] = $c->contact->idContact;
		}
			if(!empty($ids)) {
			// Consultar la lista de campos personalizados para esos contactos
			$finstancesO = Fieldinstance::findInstancesForMultipleContacts($ids);

			// Consultar lista de campos personalizados de la base de datos
			$cfieldsO = Customfield::findCustomfieldsForDbase($list->dbase);


			// Convertir la lista de campos personalizados y de instancias a arreglos
			$cfields = array();
			foreach ($cfieldsO as $cf) {
				$cfields[$cf->idCustomField] = array('id' => $cf->idCustomField, 'type' => $cf->type, 'name' => 'campo' . $cf->idCustomField);
			}
			unset($cfieldsO);

			$finstances = $this->createFieldInstanceMap($finstancesO);

			foreach ($contacts as $contact) {
				//$contactT = Contact::findFirstByIdContact($contact->idContact);
				$result[] = $this->convertCompleteContactToJson($contact, $cfields, $finstances);
			}
		}
		$this->pager->setRowsInCurrentPage(count($result));
		return array('contacts' => $result, 'meta' => $this->pager->getPaginationObject() );
		
	}
	
	public function createFieldInstanceMap($finstancesO)
	{
		$finstances = array();
		foreach ($finstancesO as $fi) {
			$key = $fi->idContact . ':' . $fi->idCustomField;
			$finstances[$key] = array('numberValue' => $fi->numberValue, 'textValue' => $fi->textValue);
		}
		return $finstances;
	}

	public function findContactsByList(Contactlist $list) 
	{
		$this->pager->setTotalRecords(Contact::countContactsInList($list));
		$contacts = Contact::findContactsInList($list, null, null, array('number' => $this->pager->getRowsPerPage(), 'offset' => $this->pager->getStartIndex()));
		
		$result = array();
		foreach ($contacts as $contact) {
			//$contactT = Contact::findFirstByIdContact($contact->idContact);
			$result[] = $this->convertContactToJson($contact);
		}
		$this->pager->setRowsInCurrentPage(count($result));
		return array('contacts' => $result, 'meta' => $this->pager->getPaginationObject() );
	}
	
	public function findContactsByValueSearch($valueSearch)
	{
		$email = Email::findFirst(
					array(
						'conditions' => 'email = ?1 AND idAccount = ?2',
						'bind' => array(
							1 => $valueSearch, 
							2 => $this->account->idAccount
						)
					) 
		);
		if ($email) {
			$matches = Contact::find(
					array(
						'conditions' => 'idEmail = ?1 AND idDbase = ?2',
						'bind' => array(
								1 => $email->idEmail,
								2 => $this->idDbase
							),
						'limit' => array(
							'number' => $this->pager->getRowsPerPage(),
							'offset' => $this->pager->getStartIndex()
						)
					)
			);
		}
		$contactos = array();
		
		if($matches) {
			$this->pager->setTotalRecords(1);
			foreach ($matches as $contact) {
				$contactos[] = $this->convertContactToJson($contact);
			}
			$this->pager->setRowsInCurrentPage(count($contactos));
			
		}
		return array('contacts' => $contactos, 'meta' => $this->pager->getPaginationObject());
	}
	
	public function findContactByAnyValue($valueSearch)
	{
		$this->md5 = md5($valueSearch);
		$contacts = array();
		
		$search = $this->validateAndGetSearchPatterns($valueSearch);
		$c = $this->getContactIds($search);
		
		if (count($c) > 0) {
			$idsContact = implode(', ', $c);
			
			$sql = "SELECT c.idContact, e.email, c.name, c.lastName, d.idDbase, d.name AS dbase
				FROM contact AS c
					JOIN email AS e ON(e.idEmail = c.idEmail)
					JOIN dbase AS d ON(d.idDbase = c.idDbase)
				WHERE c.idContact IN (" . $idsContact . ")";
		
			Phalcon\DI::getDefault()->get('logger')->log("Sql: " . $sql);
			$r = $this->db->query($sql);
			$allContacts = $r->fetchAll();
			$count = count($allContacts);

			if($count > 0) {
				foreach ($allContacts as $c) {
					$contacts[] = array(
						'id' => $c['idContact'],
						'email' => $c['email'],
						'name' => $c['name'],
						'lastName' => $c['lastName'],
						'idDbase' => $c['idDbase'],
						'dbase' => $c['dbase']
					);
				}
			}
		}
		$total = array('total' => $this->countContact);
		
		return array('contact' => $contacts, 'meta' => $total);
	}
	
	private function validateAndGetSearchPatterns($text)
	{
		Phalcon\DI::getDefault()->get('logger')->log("Text: " . $text);
		$array = explode(' ', $text);
		
		$emails = array();
		$domains = array();
		$texts = array();
		$values = array();
		foreach ($array as $a) {
			if (!empty($a) && !in_array($a, $values)) {
				if (filter_var($a, FILTER_VALIDATE_EMAIL)) {
					list($user, $edomain) = preg_split("/@/", $a, 2);
					if (in_array($edomain, $values)) {
						$key = array_search($edomain, $values);
						 unset($values[$key]);
					}
					$values[] = $a;
					$emails[] = $a;
				}
				else if (substr($a, 0, 1) == '@') {
					$domain = substr($a, 1);
					$emails = array();
					foreach ($values as $v) {
						if(filter_var($v, FILTER_VALIDATE_EMAIL)) {
							list($user, $edomain) = preg_split("/@/", $v, 2);
							$emails[] = $edomain;
						}
					}
					
					if (!in_array($domain, $emails)) {
						$values[] = $a;
						$domains[] = $domain;
					}
				}
				else {
					$values[] = $a;
					$texts[] = $a;
				}
			}
		}
		
		$search = new stdClass();
		
		$search->email = $emails;
		$search->domain = $domains;
		$search->text = $texts;
		
		Phalcon\DI::getDefault()->get('logger')->log("Search: " . print_r($search, true));
		return $search;
	}
	
	
	private function getContactIds($search)
	{
		$this->countContact = 0;
		$cache = Phalcon\DI::getDefault()->get('cache');
		$memContacts = $cache->get($this->md5);
		
		if (!$memContacts) {
//			Phalcon\DI::getDefault()->get('logger')->log("No hay memcache");
			$byEd = $this->getSqlForEmailAndDomainSearch($search->email, $search->domain);
			$byText = $this->getSqlForTextSearch($search->text);

			$sql = $this->getFinalSql($byEd, $byText);

			$result = $this->db->query($sql);
			$allContacts = $result->fetchAll();
			
			$contacts = array();
			if (count($allContacts) > 0) {
//				Phalcon\DI::getDefault()->get('logger')->log("Contacts: " . print_r($allContacts, true));
				foreach ($allContacts as $contact) {
					$contacts[] = $contact['idContact'];
				}
				
				$this->countContact = count($contacts);
				if ($this->countContact > 50) {
					$cache->save($this->md5, $contacts, 300);
					$c = $contacts;
					unset($contacts);
					for ($i = 0; $i < 50; $i++) {
						$contacts[] = $c[$i];
					}
				}
			}
		}
		else {
			Phalcon\DI::getDefault()->get('logger')->log("Hay memcache");
			$contacts = $memContacts;
			$cache->delete($this->md5);
		}
		return $contacts;
	}
	
	
	private function getSqlForEmailAndDomainSearch($email, $domain)
	{
		$sqlEmail = '';
		if (count($email) > 0) {
			$union = false;
			foreach ($email as $value) {
				if (!$union) {
					$union = true;
				}
				else {
					$sqlEmail .= " UNION ";
				}
				$sqlEmail .= 'SELECT e.idEmail
						 FROM email AS e
						 WHERE e.email = "' . $value . '" AND e.idAccount = ' . $this->account->idAccount;
			}
		}
		
		$sqlDomain = '';
		if (count($domain) > 0) {
			$union = false;
			foreach ($domain as $value) {
				if (!$union) {
					$union = true;
				}
				else {
					$sqlDomain .= " UNION ";
				}
				$sqlDomain .= 'SELECT e.idEmail
						 FROM domain AS d
							JOIN email AS e ON (e.idDomain = d.idDomain)
						 WHERE d.name = "' . $value . '" AND e.idAccount = ' . $this->account->idAccount;
			}
		}
		
		if ($sqlEmail == '' || $sqlDomain == '') {
			$union = '';
		}
		else {
			$union = ' UNION ';
		}
		
		$sql = $sqlEmail . $union . $sqlDomain;
		
//		Phalcon\DI::getDefault()->get('logger')->log("sql: " . $sql);
		return $sql;
	}
	
	private function getSqlForTextSearch($array)
	{
		$sql = '';
		if (count($array) > 0) {
			$criteria = implode(' ', $array);
//			Phalcon\DI::getDefault()->get('logger')->log("Criteria: " . $criteria);
			
			$sql = "SELECT c.idContact, c.idEmail
					FROM contact AS c
						JOIN dbase AS b ON(b.idDbase = c.idDbase)
					WHERE 
						MATCH(c.name, c.lastname) AGAINST ('" . $criteria . "' IN BOOLEAN MODE) 
						AND b.idAccount = " . $this->account->idAccount;
		}
		Phalcon\DI::getDefault()->get('logger')->log("Sql: " . $sql);
		return $sql;
	}
	
	private function getFinalSql($c1, $c2)
	{
		if ($c1 == '') {
			$sql = $c2 . " LIMIT " . self::DEFAULT_LIMIT_SEARCH;
		}
		else if ($c2 == '') {
			$sql = "SELECT c.idContact FROM contact AS c JOIN (" . $c1 . ") AS c1 ON (c1.idEmail = c.idEmail) LIMIT " . self::DEFAULT_LIMIT_SEARCH;
		}
		else {
			$sql = "SELECT idContact FROM (" . $c1 . ") AS c1 JOIN (" . $c2 . ") AS c2 ON (c1.idEmail = c2.idEmail) LIMIT " . self::DEFAULT_LIMIT_SEARCH;
		}
		
		Phalcon\DI::getDefault()->get('logger')->log("Final Sql: " . $sql);
		
		return $sql;
	}
}
