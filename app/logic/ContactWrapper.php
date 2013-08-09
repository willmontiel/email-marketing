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
	
	public function setIdDbase($idDbase)
	{
		$this->idDbase = $idDbase;
	}
	
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}

	public function createNewContactFromJsonData($data)
	{
		// Verificar existencia del correo en la cuenta actual
		$email = Email::findFirst(
				array(
					'conditions' => 'idAccount = ?1 AND email = ?2',
					'bind' => array(1 => $this->account->idAccount, 2 => $data->email)
				)
		);
		
		if ($email) {
			// Validar que no exista ya un contacto con ese correo dentro de la base de datos
			$contact = Contact::findFirst(
				array(
					'conditions' => 'idDbase = ?1 AND idEmail = ?2',
					'bind' => array(1 => $this->idDbase, 2 => $email->idEmail)
				)
			);
			if ($contact) {
				throw new \Exception('Contacto ya existe en la base de datos: [' . $this->idDbase . '], [' . $email->idEmail .']');
			}
			// No existe, crear el nuevo contacto
			$this->addContact($data, $email);
		}
		else {
			$this->addContact($data);
		}
		
		return $this->contact;
		
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
		
		$this->assignDataToContact($this->contact, $data);
		
		if (!$this->contact->save()) {
			$errmsg = $this->contact->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al crear el contacto: >>' . $msg . '<<');
		}
	}
	
	protected function assignDataToContact($contact, $data)
	{
		$hora = time();
		
		$contact->name = $data->name;
		$contact->lastName = $data->last_name;
		
		if ($contact->unsubscribed != 0 && $data->is_subscribed) {
			// Actualmente des-suscrito y se actualiza a suscrito
			$contact->unsubscribed = 0;
			$contact->subscribedon = $hora;
			$contact->ipSubscribed = $this->getIP();
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
			$contact->ipActivated = $this->getIP();
		}
		
	}
	
	protected function getIP()
	{
		return ip2long($_SERVER['REMOTE_ADDR']);
	}


	protected function createEmail($email)
	{
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
