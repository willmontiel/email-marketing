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
			$optin = new NotificationMail();
			$optin->sendMailInForm($contact, $this->form->optinMail);
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
			if(!isset($obj->$name)) {
				$obj->$name = '';
			}
		}
	}
	
}

?>
