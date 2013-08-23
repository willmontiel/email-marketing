<?php

class ContactCounter
{
	protected $allStatus;
	
	public function setAllStatus($allStatus) {
		$this->allStatus = $allStatus;
	}

		public function newContactCount($contact)
	{
		if (empty($this->allStatus)) {
			$this->allStatus = array(
				"idDbase" => $contact->idDbase,
				"Ctotal" => 0,
				"Cactive" => 0,
				"Cinactive" => 0,
				"Cunsubscribed" => 0,
				"Cbounced" => 0,
				"Cspam" => 0
			);
		}
		
		$this->assignNewContactCount($contact);
	}
	
	public function assignNewContactCount($contact)
	{
		$this->allStatus["Ctotal"]++;
		
		if ($contact->status != 0) {
			$this->allStatus["Cactive"]++;
		} else {
			$this->allStatus["Cinactive"]++;
		}
		
		if($contact->unsubscribed != 0) {
			$this->allStatus["Cunsubscribed"]++;
		}
		if($contact->bounced != 0) {
			$this->allStatus["Cbounced"]++;
		}
		
		if($contact->spam != 0) {
			$this->allStatus["Cspam"]++;
		}
	}
	
	public function saveCount()
	{	
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$db = Dbase::findFirstByIdDbase($this->allStatus["idDbase"]);
		
		$actTotal = $db->Ctotal;
		$actActive = $db->Cactive;
		$actInactive = $db->Cinactive;
		$actUnsubscribed = $db->Cunsubscribed;
		$actBounced = $db->Cbounced;
		$actSpam = $db->Cspam;

		$parameters = array('idDbase' => $this->allStatus["idDbase"], 
							'Ctotal' => $this->allStatus["Ctotal"] + $actTotal, 
							'Cactive' => $this->allStatus["Cactive"] + $actActive, 
							'Cinactive' => $this->allStatus["Cinactive"] + $actInactive, 
							'Cunsubscribed' => $this->allStatus["Cunsubscribed"] + $actUnsubscribed,
							'Cbounced' => $this->allStatus["Cbounced"] + $actBounced,
							'Cspam' => $this->allStatus["Cspam"] + $actSpam
				);
		

		
		$query = "UPDATE Dbase SET Ctotal = :Ctotal:, Cactive = :Cactive:, Cinactive = :Cinactive:, Cunsubscribed =  :Cunsubscribed:, Cbounced = :Cbounced:, Cspam = :Cspam: WHERE idDbase = :idDbase:";
		$query2 = $modelManager->createQuery($query);
        $result = $query2->execute($parameters);
		$this->allStatus = "";
	}

		public function deleteContactCount($contact)
	{
		$db = Dbase::findFirstByIdDbase($contact->idDbase);
		
		$db = $this->assignDeleteContactCount($db, $contact);
		
		if (!$db->save()) {
			throw new \Exception('Error al disminuir el contador de los contactos');
		}
	}
	
	public function assignDeleteContactCount($obj, $contact)
	{
		$obj->Ctotal--;
		
		if ($contact->status != 0) {
			$obj->Cactive--;
		} else {
			$obj->Cinactive--;
		}
		
		if($contact->unsubscribed != 0) {
			$obj->Cunsubscribed--;
		}
		
		if($contact->bounced != 0) {
			$obj->Cbounced--;
		}
		
		if($contact->spam != 0) {
			$obj->Cspam--;
		}
		
		return $obj;
	}


	public function updateContactCount($oldcontact, $newcontact)
	{
		$db = Dbase::findFirstByIdDbase($newcontact->idDbase);

		if($newcontact->status != $oldcontact->status) {
			if ($newcontact->status != 0) {
				$db->Cactive++;
				$db->Cinactive--;
			} else {
				$db->Cinactive++;
				$db->Cactive--;
			}
		}
		if($newcontact->unsubscribed != $oldcontact->unsubscribed) {
			if($newcontact->unsubscribed != 0){
				$db->Cunsubscribed++;
			} else {
				$db->Cunsubscribed--;
			}
		}
		
		if($newcontact->bounced != $oldcontact->bounced) {
			if($newcontact->bounced != 0){
				$db->Cbounced++;
			} else {
				$db->Cbounced--;
			}
		}

		if($newcontact->spam != $oldcontact->spam) {
			if($contact->spam != 0){
				$db->spam--;
			} else {
				$db->spam++;
			}
		}
		
		if (!$db->save()) {
			throw new \Exception('Error al actualizar el contador de los contactos');
		}
	}
	
	public function newContactByListCount($idContact, $idList)
	{
		$list = Contactlist::findFirstByIdList($idList);
		$contact = Contact::findFirstByIdContact($idContact);
		
		$list = $this->assignNewContactCount($list, $contact);
		
		if (!$list->save()) {
			throw new \Exception('Error al aumentar el contador de los contactos');
		}
	}
	
	public function deleteContactByListCount($contact, $idList)
	{
		$list = Contactlist::findFirstByIdList($idList);
		
		$list = $this->assignDeleteContactCount($list, $contact);
		
		if (!$list->save()) {
			throw new \Exception('Error al aumentar el contador de los contactos');
		}
	}
	
}