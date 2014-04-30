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

	public function createContactFromForm($fields)
	{
		$contactObj = new stdClass();

		foreach ($fields as $key => $value) {
			
			$param = explode("_", $key);
			$fieldname = $param[1];
			
			if($fieldname === 'email' || $fieldname === 'name' || $fieldname === 'lastName') {
				$name = $fieldname;
			}
			else {
				$name = 'campo' . $fieldname;
			}
			
			$contactObj->$name = (is_array($value)) ? implode(',', $value) : $value ;
		}

		$this->setAllValuesInContact($contactObj);
		
		if (!isset($contactObj->email) || trim($contactObj->email) == '') {
			throw new \Exception('El email es requerido');
		}

		$contact = $this->addExistingContactToListFromDbase($contactObj->email, $this->contactlist);
		if($contact == false) {
			$contact = $this->createNewContactFromJsonData($contactObj, $this->contactlist);
		}
		
		if($contact) {
			
			$domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
			
			if($this->form->optin === 'Si') {
				try {
					$content = json_decode($this->form->optinMail);

					$optin = new NotificationMail();
					$optin->setForm($this->form);
					$optin->setAccount($this->account);
					$optin->setDomain($domain);
					$optin->setContact($contact);
					$optin->setMail(new Mail());
					$optin->prepareContent($content->mail);
					$optin->setNotificationLink();
					$optin->setContactReceiver();
					$optin->sendMail($content);
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
						$notify->setMail(new Mail());
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
		
		$cfs = Customfield::findByIdDbase($this->contactlist->idDbase);
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
				$notify->setAccount($this->account);
				$notify->setDomain($domain);
				$notify->setMail(new Mail());
				$notify->prepareContent($content->mail);
				$notify->setNotifyReceiver($this->form->notifyEmail, '');
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
