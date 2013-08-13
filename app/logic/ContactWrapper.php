<?php


/**
 * Description of ContactWrapper
 *
 * @author hectorlasso
 */
class ContactWrapper 
{
	protected $idDbase;
	protected $account;
	protected $contact;
	protected $ipaddress;
	
	
	const PAGE_DEFAULT = 20;

	public function __construct()
	{
		$this->ipaddress = ip2long('127.0.0.0');
	}

	public function setIdDbase($idDbase)
	{
		$this->idDbase = $idDbase;
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}
	
	public function setIPAdress($ipaddress) {
		$this->ipaddress = ip2long($ipaddress);
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
			
			// Asignar el nuevo email
			$contact->email = $email;
			
		}

		$this->contact = $contact;
		
		// 6) Actualizar los otros campos
		$this->assignDataToContact($contact, $data);
		

		// 7) Grabar cambios
		if (!$this->contact->save()) {
			$errmsg = $this->contact->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al crear el contacto: >>' . $msg . '<<');
		}		
		
		return $this->contact;
	}

	public function createNewContactFromJsonData($data)
	{
		// Verificar existencia del correo en la cuenta actual
		$email = $this->findEmailNotRepeated($data->email);
		if ($email) {
			$this->addContact($data, $email);
		}
		else {
			$this->addContact($data);
		}
		
		return $this->contact;
		
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
					throw new \InvalidArgumentException('El correo electronico [' . $emailaddress .  '] ya existe en la base de datos');
				}
			}
		}
		// Retornar el email
		return $email;
	}
	
	protected function addContact($data, Email $email = null)
	{
		if ($email == null) {
			$email = $this->createEmail($data->email);
		}
		
		$this->contact = new Contact();

		$this->contact->account = $this->account;
		$this->contact->email = $email;
		$this->contact->bounced = 0;
		$this->contact->spam = 0;
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
	}
	
	protected function assignDataToContact($contact, $data, $isnew = false)
	{
		$hora = time();
		
		$contact->name = $data->name;
		$contact->lastName = $data->last_name;

		if ($isnew) {
			$contact->ipSubscribed = $this->ipaddress;
			$contact->ipActivated = 0;
			$contact->unsubscribed = (!$data->is_subscribed)?$hora:0;
			$contact->subscribedon = ($data->is_subscribed)?$hora:0;
			$contact->ipSubscribed = $this->ipaddress;
			$contact->status = ($data->is_active)?$hora:0;
			$contact->ipActivated = ($data->is_active)?$this->ipaddress:0;
		}
		else {
			if ($contact->unsubscribed != 0 && $data->is_subscribed) {
				// Actualmente des-suscrito y se actualiza a suscrito
				$contact->unsubscribed = 0;
				$contact->subscribedon = $hora;
				$contact->ipSubscribed = $this->ipaddress;
			}
			else if ($contact->unsubscribed == 0 && !$data->is_subscribed) {
				// Actualmente suscrito, y se actualiza a des-suscrito
				$contact->unsubscribed = $hora;
			}

			if ($contact->status != 0 && !$data->is_active) {
				// Actualmente activo, y se desactiva
				$contact->status = 0;
			}
			else if ($contact->status == 0 && $data->is_active) {
				// Actualmente desactivado y se activa
				$contact->status = $hora;
				$contact->ipActivated = $this->ipaddress;
			}
			
		}
	}
	
	protected function createEmail($email)
	{
		if (!\filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException('La direccion [' . $email . '] no es una direccion de correo valida!');
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
	
	public function findContacts($page = 1, $limit = null)
	{
		// Buscar los contactos
		$options = array();

		if ($this->filter) {
			$options['conditions'] = 'email = ?1';
			$options['bind'] = array(1 => $this->filter);
		}
		$npage = ($limit)?$limit:self::PAGE_DEFAULT;
		$start = ($page - 1) * $npage; 
		$options['limit'] = array('limit' => $npage, 'offset' => $start);
		$contacts = Contact::find(array('limit' => array('number' => ContactWrapper::PAGE_DEFAULT, 'offset' => 0)));

		$result = array();
		foreach ($contacts as $contact) {
			$result[] = $this->convertContactToJson($contact);
		}
		
		return array('contacts' => $result, 'meta' => array( 'pagination' => array('page' => 1, 'limit' => 2, 'total' => 10000) ));
	}
	
	public function convertContactToJson($contact)
	{
		$object = array();
		$object['id'] = $contact->idContact;
		$object['email'] = $contact->email->email;
		$object['name'] = $contact->name;
		$object['last_name'] = $contact->lastName;
		$object['is_active'] = ($contact->status != 0);
		$object['activated_on'] = (($contact->status != 0)?date('d/m/Y H:i', $contact->status):'');
		$object['is_subscribed'] = ($contact->unsubscribed == 0);
		$object['subscribed_on'] = (($contact->subscribedon != 0)?date('d/m/Y H:i', $contact->subscribedon):'');
		$object['unsubscribed_on'] = (($contact->unsubscribed != 0)?date('d/m/Y H:i', $contact->unsubscribed):'');
		$object['is_bounced'] = ($contact->bounced != 0);
		$object['bounced_on'] = (($contact->bounced != 0)?date('d/m/Y H:i', $contact->bounced):'');
		$object['is_spam'] = ($contact->spam != 0);
		$object['spam_on'] = (($contact->spam != 0)?date('d/m/Y H:i', $contact->spam):'');
		$object['created_on'] = (($contact->createdon != 0)?date('d/m/Y H:i', $contact->createdon):'');
		$object['updated_on'] = (($contact->updatedon != 0)?date('d/m/Y H:i', $contact->updatedon):'');
		
		$object['ip_subscribed'] = (($contact->ipSubscribed)?long2ip($contact->ipSubscribed):'');
		$object['ip_activated'] = (($contact->ipActivated)?long2ip($contact->ipActivated):'');
		
		return $object;
	}
}
