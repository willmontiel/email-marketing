<?php

class ContactFormWrapper extends ContactWrapper
{
	function __construct() {
		parent::__construct();
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	
	public function setContactlist(Contactlist $contactlist)
	{
		$this->contactlist = $contactlist;
	}
	
	public function setForm(Form $form)
	{
		$this->form = $form;
	}
	
	protected function setFieldsToWrapper($fields)
	{
		$contactObj = new stdClass();

		foreach ($fields as $key => $value) {
			
			$param = explode("_", $key);
			$fieldname = $param[1];
			
			if($fieldname === 'email' || $fieldname === 'name' || $fieldname === 'lastName' || $fieldname === 'birthDate') {
				$name = $fieldname;
			}
			else {
				$name = 'campo' . $fieldname;
			}
			
			if(isset($param[2]) && ($param[2] === 'day' || $param[2] === 'month' || $param[2] === 'year') && isset($contactObj->$name)){
				$value = trim($value);
				$contactObj->$name.= '/'.$value;
				if($param[2] === 'year' && (empty($value) || $value == null)){
					$contactObj->$name = null;
				}
			}
			else {
				$contactObj->$name = (is_array($value)) ? implode(',', $value) : $value ;
			}
		}

		$this->setAllValuesInContact($contactObj);
		
		if (!isset($contactObj->email) || trim($contactObj->email) == '') {
			throw new \Exception('El email es requerido');
		}
		
		return $contactObj;
	}

	public function createContactFromForm($fields)
	{
		$contactObj = $this->setFieldsToWrapper($fields);
		$this->logger->log(print_r($fields, true));
		$this->logger->log('Nuevo contacto por formulario');
		$this->logger->log(print_r($contactObj, true));
		$contact = $this->addExistingContactToListFromDbase($contactObj->email, $this->contactlist, true, true);
		
		if($contact == false) {
			$contact = $this->createNewContactFromJsonData($contactObj, $this->contactlist);
		}
		
		if($contact) {
			
			$domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
			
			if($this->form->optin === 'Si') {
				try {
					$this->logger->log('Inicio Envio de Mensaje de Optin');
					$content = json_decode($this->form->optinMail);

					$optin = new NotificationMail();
					$optin->setForm($this->form);
					$optin->setAccount($this->account);
					$optin->setDomain($domain);
					$optin->setContact($contact);
					$optin->setMail(new Mail());
					$optin->setSubject($content->subject);
					$optin->prepareContent($content->mail);
					$optin->setNotificationLink();
					$optin->setContactReceiver();
					$optin->sendMail($content);
					$this->logger->log('Finalizo Envio de Mensaje de Optin');
				} catch (\Exception $e) {
					$this->logger->log('Exception: [' . $e->getMessage() . ']');
				}
			}
			else {
				if($this->form->notify === 'Si') {
					try {
						$content = json_decode($this->form->notifyMail);

						$notify = new NotificationMail();
						$notify->setForm($this->form);
						$notify->setAccount($this->account);
						$notify->setDomain($domain);
						$notify->setContact($contact);
						$notify->setMail(new Mail());
						$notify->setSubject($content->subject);
						$notify->prepareContent($content->mail);
						$notify->setNotifyReceiver($this->form->notifyEmail, $this->form->notifyEmail);
						$notify->sendMail($content);
					} catch (\Exception $e) {
						$this->logger->log('Exception: [' . $e->getMessage() . ']');
					}
				}

				if($this->form->welcome === 'Si') {
					try {
						$content = json_decode($this->form->welcomeMail);

						$welcome = new NotificationMail();
						$welcome->setForm($this->form);
						$welcome->setContact($contact);
						$welcome->setAccount($this->account);
						$welcome->setDomain($domain);
						$welcome->setMail(new Mail());
						$welcome->setSubject($content->subject);
						$welcome->prepareContent($content->mail);
						$welcome->setContactReceiver();
						$welcome->sendMail($content);
					} catch (\Exception $e) {
						$this->logger->log('Exception: [' . $e->getMessage() . ']');
					}
				}
			}
		}
	}
	
	protected function setAllValuesInContact($obj)
	{
		$obj->status = '';
		$obj->activatedOn = '';
		$obj->bouncedOn = '';
		$obj->unsubscribed = '';
		$obj->subscribedon = '';
		$obj->spamOn = '';
		$obj->ipActive = '';
		$obj->ipSubscribed = '';
		$obj->updatedOn = '';
		$obj->createdOn = '';
		$obj->isBounced = '';
		$obj->isSubscribed = true;
		$obj->isSpam = '';
		$obj->isActive = false;
		$obj->isEmailBlocked = '';
		$obj->mailHistory = '';
		
		if($this->form->optin === 'No') {
			$obj->isActive = true;
			$obj->isSubscribed = true;
		}
		
		$idDbase = (isset($this->contactlist)) ? $this->contactlist->idDbase : $this->idDbase;
		
		$cfs = Customfield::findByIdDbase($idDbase);
		foreach ($cfs as $cf) {
			$name = 'campo'.$cf->idCustomField;			
			if(!isset($obj->$name)) {
				$obj->$name = '';
			}
		}
	}
	
	public function activateContactFromForm(Contact $contact)
	{
		$oldContact = Contact::findFirstByIdContact($contact->idContact);
		
		$contact->status = time();
		$contact->updatedon = time();
		$contact->ipActivated = $this->ipaddress;
		
		if (!$contact->save()) {
			$errmsg = $contact->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al actualizar el contacto: >>' . $msg . '<<');
		}
		$counter = new ContactCounter();
		$counter->updateContact($oldContact, $contact);
		$counter->saveCounters();
		
		$domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
		
		if($this->form->notify === 'Si') {
			try {
				$content = json_decode($this->form->notifyMail);
				
				$notify = new NotificationMail();
				$notify->setForm($this->form);
				$notify->setContact($contact);
				$notify->setAccount($this->account);
				$notify->setDomain($domain);
				$notify->setMail(new Mail());
				$notify->setSubject($content->subject);
				$notify->prepareContent($content->mail);
				$notify->setNotifyReceiver($this->form->notifyEmail, $this->form->notifyEmail);
				$notify->sendMail($content);
			} catch (\Exception $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
		}
		
		if($this->form->welcome === 'Si') {
			try {
				$content = json_decode($this->form->welcomeMail);

				$welcome = new NotificationMail();
				$welcome->setForm($this->form);
				$welcome->setContact($contact);
				$welcome->setAccount($this->account);
				$welcome->setDomain($domain);
				$welcome->setMail(new Mail());
				$welcome->setSubject($content->subject);
				$welcome->prepareContent($content->mail);
				$welcome->setContactReceiver();
				$welcome->sendMail($content);
			} catch (\Exception $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
		}
	}
	
	public function updateContactFromForm($idContact, $fields)
	{
		$contactObj = $this->setFieldsToWrapper($fields);
		$this->logger->log('Actualizacion de contacto por formulario');
		$this->logger->log(print_r($contactObj, true));
		$contact = $this->updateContactFromJsonData($idContact, $contactObj);
		
		$domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
		
		if($this->form->notify === 'Si') {
			try {
				$content = json_decode($this->form->notifyMail);
				
				$updatenotify = new NotificationMail();
				$updatenotify->setForm($this->form);
				$updatenotify->setContact($contact);
				$updatenotify->setAccount($this->account);
				$updatenotify->setDomain($domain);
				$updatenotify->setMail(new Mail());
				$updatenotify->setSubject($content->subject);
				$updatenotify->prepareContent($content->mail);
				$updatenotify->setNotifyReceiver($this->form->notifyEmail, $this->form->notifyEmail);
				$updatenotify->sendMail($content);
			} catch (\Exception $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
		}
		
		if($this->form->welcome === 'Si') {
			try {
				$content = json_decode($this->form->welcomeMail);

				$welcome = new NotificationMail();
				$welcome->setForm($this->form);
				$welcome->setContact($contact);
				$welcome->setAccount($this->account);
				$welcome->setDomain($domain);
				$welcome->setMail(new Mail());
				$welcome->setSubject($content->subject);
				$welcome->prepareContent($content->mail);
				$welcome->setContactReceiver();
				$welcome->sendMail($content);
			} catch (\Exception $e) {
				$this->logger->log('Exception: [' . $e->getMessage() . ']');
			}
		}
	}
	
}

?>
