<?php

class TestController extends ControllerBase
{
	public $result;
	
	public function indexAction()
	{
		
	}

	public function testcountersAction()
	{
		$email = "holacomotah@hola.com";
		$idDbase = 1156;
		$idList = 5;
		$log = $this->logger;
		
		$db = Dbase::findFirstByIdDbase($idDbase);
		$list = Contactlist::findFirstByIdList($idList);
		
		$array[0] = $this->fullArray($db, $list);
		$array[0]['Desc'] = "Estado Inicial";

		//-----------------New Contact-----------------//
		
		$contact = $this->createContact($email, $idDbase, $idList);
		
		$idContact = $contact->idContact;
		
		$db = Dbase::findFirstByIdDbase($idDbase);
		$list = Contactlist::findFirstByIdList($idList);
		
		$array[1] = $this->fullArray($db, $list);
		$array[1]['Desc'] = "Nuevo Contacto";

		//-----------------Update Contact-----------------//
	
		$this->updateContact($email, $idDbase, $idContact);
		
		$db = Dbase::findFirstByIdDbase($idDbase);
		$list = Contactlist::findFirstByIdList($idList);
		
		$array[2] = $this->fullArray($db, $list);
		$array[2]['Desc'] = "Contacto Actualizado";
		
		
		$this->view->setVar("results", $array);	
	}
	
	protected function updateContact($email, $idDbase, $idContact)
	{
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		$contact = $this->createContactObj($email, "", 1, "", "");
		
		//$wrapper->updateContactFromJsonData($idContact, $contact);
	}	

	protected function createContact($email, $idDbase, $idList)
	{
		$wrapper = new ContactWrapper();
		
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIdList($idList);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		
		$contact = $this->createContactObj($email, 1, 1, "", "");
		
		//$wrapper->createNewContactFromJsonData($contact);
	}

	protected function createContactObj($email, $isactive = "", $issubscribed = "", $isbounced = "", $isspam = "")
	{
		$obj = new stdClass();
		
		$obj->email = $email;
		$obj->name = "";
		$obj->last_name = "";
		$obj->status = "";
		$obj->activated_on = "";
		$obj->bounced_on = "";
		$obj->subscribed_on = "";
		$obj->unsubscribed_on = "";
		$obj->spam_on = "";
		$obj->ip_active = "";
		$obj->ip_subscribed = "";
		$obj->updated_on = "";
		$obj->created_on = "";
		$obj->is_bounced = $isbounced;
		$obj->is_subscribed = $issubscribed;
		$obj->is_spam = $isspam;
		$obj->is_active = $isactive;
		
		return $obj;
	}
	
	protected function fullArray($db, $list)
	{
		$array = array(
			"CtotalDB" => $db->Ctotal,
			"CactiveDB" => $db->Cactive,
			"CunsubscribedDB" => $db->Cunsubscribed,
			"CbouncedDB" => $db->Cbounced,
			"CspamDB" => $db->Cspam,
			"CtotalList" => $list->Ctotal,
			"CactiveList" => $list->Cactive,
			"CunsubscribedList" => $list->Cunsubscribed,
			"CbouncedList" => $list->Cbounced,
			"CspamList" => $list->Cspam
		);
		return $array;
	}
}