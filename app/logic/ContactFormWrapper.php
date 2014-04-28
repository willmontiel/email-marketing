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
		
		if($contact && $this->form->optin === 'Si') {
			$content = json_decode($this->form->optinMail);
			$domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
			
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
		}
	}
	
	protected function setAllValuesInContact($obj)
	{
		$obj->status = '';
		$obj->activatedOn = '';
		$obj->bouncedOn = '';
		$obj->subscribedOn = '';
		$obj->unsubscribedOn = '';
		$obj->spamOn = '';
		$obj->ipActive = '';
		$obj->ipSubscribed = '';
		$obj->updatedOn = '';
		$obj->createdOn = '';
		$obj->isBounced = '';
		$obj->isSubscribed = false;
		$obj->isSpam = '';
		$obj->isActive = false;
		$obj->isEmailBlocked = '';
		$obj->mailHistory = '';
		
		$cfs = Customfield::findByIdDbase($this->contactlist->idDbase);
		foreach ($cfs as $cf) {
			$name = 'campo'.$cf->idCustomField;
			if($cf->type === 'Date') {
				if(isset($obj->$name)) {
					$obj->$name = strtotime($obj->$name);
				}
			}
			
			if(!isset($obj->$name)) {
				$obj->$name = '';
			}
		}
	}
	
	public function activateContactFromForm(Contact $contact)
	{
		$contact->status = time();
		$contact->updatedon = time();
		$contact->unsubscribed = 0;
		$contact->subscribedon = time();
		$contact->ipActivated = $this->ipaddress;
		
		if (!$contact->save()) {
			$errmsg = $contact->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al actualizar el contacto: >>' . $msg . '<<');
		}
		
		$domain = Urldomain::findFirstByIdUrlDomain($this->account->idUrlDomain);
		
		if($this->form->notify === 'Si') {
			$content = json_decode($this->form->notifyMail);
			
			$notify = new NotificationMail();
			$notify->setForm($this->form);
			$notify->setAccount($this->account);
			$notify->setDomain($domain);
			$notify->setMail(new Mail());
			$notify->prepareContent($content->mail);
			$notify->setNotifyReceiver($this->form->notifyEmail, '');
			$notify->sendMail($content);
		}
		
		if($this->form->welcome === 'Si') {
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
		}
	}
	
}

?>
