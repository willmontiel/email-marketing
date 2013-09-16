<?php

class TestController extends ControllerBase
{
	protected $idDbase;

	protected $idContactlist;

	protected $idContactlistNew;

	public function setIdDbase($idDbase) {
			$this->idDbase = $idDbase;
	}

	public function setIdContactlist($idContactlist) {
			$this->idContactlist = $idContactlist;
	}

	public function setIdContactlistNew($idContactlistNew) {
			$this->idContactlistNew = $idContactlistNew;
	}

	public function indexAction()
	{

	}

	public function testtransactionAction()
	{
//		$manager = new Phalcon\Mvc\Model\Transaction\Manager();
//	    $transaction = $manager->get();
				
		try {
			$this->db->begin();
		$contact = new Contact();
		
//		$contact->setTransaction($transaction);
		
		$contact->idDbase = 1155;
		$contact->idEmail = 638;
		$contact->name = "Pepitos";
		$contact->bounced = 0;
		$contact->unsubscribed = 0;
		$contact->spam = 0;
		$contact->ipActivated= 2130706433;
		$contact->ipSubscribed= 2130706433;
		$contact->updatedon = 1378332895;
		$contact->lastName = "Perez";
		$contact->subscribedon = 1378332895;
		$contact->status = 1378332895;
		$contact->createdon = 1378332895;
			
		if(!$contact->save()) {
//			$transaction->rollback("No se pudo guardar el contacto");
			$this->db->rollback();
		}
		//$transaction->commit();
		
		$associate = new Coxcl();
		
//		$associate->setTransaction($transaction);
		
		$associate->idContactlist = 1;
		$associate->contact = $contact;
		$t = '';
		
		if(!$associate->save()) {
			foreach ($associate->getMessages() as $m) {
				$t = $m->getMessage() . '<br/>';
			}
//			$transaction->rollback("No se pudo crear la asociacion");
			$this->db->rollback();
		}
		$this->db->commit();

//		$transaction->commit();

		$contactnew = Contact::findFirstByIdContact($contact->idContact);
		$associatenew = Coxcl::findFirstByIdContact($contact->idContact);
		
		$this->view->setVar("contact", $contactnew);	
		$this->view->setVar("associate", $associatenew);
			$this->view->setVar('txterror', $t);
		}
		catch (Exception $e) {
			echo 'Proceso fallido!';
			$this->view->setVar('txterror', $t . ',' . $e);
		}
	}
	
	public function testcountersAction()
	{
		$array = $this->secondTest();
		$this->view->setVar("results", $array); 
	}	
	
	public function firstTest()
	{
			$email = "newone@newone.com";
			$emailNew = "other@other.com";

			$this->setIdDbase(1155);
			$this->setIdContactlist(1);
			$this->setIdContactlistNew(14);

			$log = $this->logger;

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[0] = $this->fullArray($db, $list, $listNew);
			$array[0]['Desc'] = "Estado Inicial";

			//-----------------New Contact Subscribed-----------------//

			$contact = $this->createContact($email);

			$idContact = $contact->idContact;

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[1] = $this->fullArray($db, $list, $listNew);
			$array[1]['Desc'] = "Nuevo Contacto Inactivo";

			//-----------------Update Contact Subscribed and Active-----------------//

			$contact = $this->createContactObj($email, 1, 1, "", "");

			$log->log("Se Suscribe y Activa: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[2] = $this->fullArray($db, $list, $listNew);
			$array[2]['Desc'] = "Suscrito y Activo";

			//-----------------Update Contact Bounced-----------------//

			$contact = $this->createContactObj($email, "", "", 1, "");

			$log->log("Se Rebota: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[3] = $this->fullArray($db, $list, $listNew);
			$array[3]['Desc'] = "Rebotado";
			
			//-----------------Update Contact Suscribed, Active and Bounced-----------------//

			$contact = $this->createContactObj($email, 1, 1, 1, "");

			$log->log("Se Suscribe, Activa y Rebota: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[4] = $this->fullArray($db, $list, $listNew);
			$array[4]['Desc'] = "Suscrito, Activo y Rebotado";

			//-----------------Update Contact Suscribed, Active and Spam-----------------//

			$contact = $this->createContactObj($email, 1, 1, "", 1);

			$log->log("Se Suscribe, Activa y Spam: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[5] = $this->fullArray($db, $list, $listNew);
			$array[5]['Desc'] = "Suscrito, Activo y Spam";

			//-----------------Update Contact Unsubscribed-----------------//

			$contact = $this->createContactObj($email, 1, "", "", "");

			$log->log("Se Des-suscribe: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[6] = $this->fullArray($db, $list, $listNew);
			$array[6]['Desc'] = "Des-suscrito";

			//-----------------Update Contact Unsubscribed and Spam-----------------//

			$contact = $this->createContactObj($email, 1, "", "", 1);

			$log->log("Se Des-suscribe y Spam: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[7] = $this->fullArray($db, $list, $listNew);
			$array[7]['Desc'] = "Des-suscrito y Spam";

			//-----------------Update Contact Unsuscribed and Bounced-----------------//

			$contact = $this->createContactObj($email, 1, "", 1, "");

			$log->log("Se Des-suscribe y Rebota: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[8] = $this->fullArray($db, $list, $listNew);
			$array[8]['Desc'] = "Des-suscrito, Rebotado";

			//-----------------Update Contact Spam and Unsubscribed-----------------//

			$contact = $this->createContactObj($email, 1, "", "", 1);

			$log->log("Se Des-suscribe y Spam: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[9] = $this->fullArray($db, $list, $listNew);
			$array[9]['Desc'] = "Spam y Des-suscrito";

			//-----------------Update Contact Unsubscribed, Spam and Bounced-----------------//

			$contact = $this->createContactObj($email, 1, "", 1, 1);

			$log->log("Se Des-suscribe, Spam y Rebotado: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[10] = $this->fullArray($db, $list, $listNew);
			$array[10]['Desc'] = "Des-suscrito, Spam y Rebotado";

			//-----------------Update Contact Bounced-----------------//

			$contact = $this->createContactObj($email, "", "", 1, "");

			$log->log("Se Rebota: " . print_r($contact, true));

			$this->updateContact($email, $idContact, $contact);

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[11] = $this->fullArray($db, $list, $listNew);
			$array[11]['Desc'] = "Rebotado";

			//-----------------Contact To New List-----------------//

			$contact = $this->createContact($email, true);
			
			$log->log("Contacto Existente a Lista Nueva");

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[12] = $this->fullArray($db, $list, $listNew);
			$array[12]['Desc'] = "Contacto Existente a Lista Nueva";

			//-----------------Again the Same Contact To New List-----------------//

			$contact = $this->createContact($email, true);

			$log->log("Crear Contacto Existente en Lista Nueva Again");
			
			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[13] = $this->fullArray($db, $list, $listNew);
			$array[13]['Desc'] = "Crear Contacto Existente en Lista Nueva Again";

			//-----------------Let's Show How the Counts Are-----------------//

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[14] = $this->fullArray($db, $list, $listNew);
			$array[14]['Desc'] = "Contadores Despues de Ingresar un Contacto Erroneo";

			//-----------------New Contact Subscribed Again-----------------//

			$contact = $this->createContact($emailNew);

			$idContact = $contact->idContact;

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[15] = $this->fullArray($db, $list, $listNew);
			$array[15]['Desc'] = "Segundo Contacto Suscrito e Inactivo";

			//-----------------New Contacts By Batch-----------------//

			for($i=0; $i<3; $i++) {
				$contact = $this->createContact($emailNew."a".$i);
			}

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[16] = $this->fullArray($db, $list, $listNew);
			$array[16]['Desc'] = "Contactos por Lote";

			//-----------------Final Result-----------------//

			$db = Dbase::findFirstByIdDbase($this->idDbase);
			$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
			$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

			$array[17] = $this->fullArray($db, $list, $listNew);
			$array[17]['Desc'] = "Finalizacion de contadores";
		
			//-----------------Show Results-----------------//
			return $array;	
	}
	
	public function secondTest()
	{
		$email = "newone@newone.com";
		$emailNew = "other@other.com";

		$this->setIdDbase(1155);
		$this->setIdContactlist(1);
		$this->setIdContactlistNew(14);

		$log = $this->logger;
		
		$this->createContact("newone0@newone.com", false, 1, 1, 1, 1);
		$this->createContact("newone1@newone.com", false, 1, 1, 0, 1);
		$this->createContact("newone2@newone.com", false, 1, 0, 1, 1);
		$this->createContact("newone3@newone.com", false, 1, 0, 0, 1);
		$this->createContact("newone4@newone.com", false, 0, 1, 1, 0);
		$this->createContact("newone5@newone.com", false, 0, 1, 0, 0);
		$this->createContact("newone6@newone.com", false, 0, 0, 1, 0);
		$this->createContact("newone7@newone.com", false, 0, 0, 0, 0);
		$this->createContact("newone8@newone.com", false, 1, 1, 1, 0);
		$this->createContact("newone9@newone.com", false, 1, 1, 0, 0);
		$this->createContact("newone10@newone.com", false, 1, 0, 1, 0);
		$this->createContact("newone11@newone.com", false, 1, 0, 0, 0);

		$db = Dbase::findFirstByIdDbase($this->idDbase);
		$list = Contactlist::findFirstByIdContactlist($this->idContactlist);
		$listNew = Contactlist::findFirstByIdContactlist($this->idContactlistNew);

		$array[0] = $this->fullArray($db, $list, $listNew);
		$array[0]['Desc'] = "Resultado de contadores";
		
		return $array;
	}

	protected function updateContact($email, $idContact, $contact)
	{
			$wrapper = new ContactWrapper();

			$wrapper->setAccount($this->user->account);
			$wrapper->setIdDbase($this->idDbase);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);

			$wrapper->updateContactFromJsonData($idContact, $contact);
	}       

	protected function createContact($email, $newlist = false, $isactive = "", $issubscribed = "", $isbounced = "", $isspam = "")
	{
			$log = $this->logger;
			$wrapper = new ContactWrapper();

			if(!$newlist) {
					$wrapper->setIdContactlist($this->idContactlist);
			} else {
					$wrapper->setIdContactlist($this->idContactlistNew);
			}               

			$wrapper->setAccount($this->user->account);
			$wrapper->setIdDbase($this->idDbase);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);

			$contact = $this->createContactObj($email, $isactive, $issubscribed, $isbounced, $isspam);

			try {
					$log->log("Envia esto" . print_r($contact, true));		
					$contactC = $wrapper->addExistingContactToListFromDbase($contact->email);
					if(!$contactC) {
							$contactC = $wrapper->createNewContactFromJsonData($contact);
					}
			}
			catch (\InvalidArgumentException $e) {
					$log->log('Exception: [' . $e . ']');
					return  NULL;
			}
			catch (\Exception $e) {
					$log->log('Exception: [' . $e . ']');
					return  NULL;
			}

			return $contactC;
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

	protected function fullArray($db, $list, $listNew)
	{
			$inactive = new ContactCounter();

			$array = array(
					"CtotalDB" => $db->Ctotal,
					"CactiveDB" => $db->Cactive,
					"CinactiveDB" => $inactive->getInactive($db),
					"CunsubscribedDB" => $db->Cunsubscribed,
					"CbouncedDB" => $db->Cbounced,
					"CspamDB" => $db->Cspam,
					"CtotalList" => $list->Ctotal,
					"CactiveList" => $list->Cactive,
					"CinactiveList" => $inactive->getInactive($list),
					"CunsubscribedContactlist" => $list->Cunsubscribed,
					"CbouncedContactlist" => $list->Cbounced,
					"CspamList" => $list->Cspam,
					"CtotalListNew" => $listNew->Ctotal,
					"CactiveListNew" => $listNew->Cactive,
					"CinactiveListNew" => $inactive->getInactive($listNew),
					"CunsubscribedContactlistNew" => $listNew->Cunsubscribed,
					"CbouncedContactlistNew" => $listNew->Cbounced,
					"CspamListNew" => $listNew->Cspam
			);
			return $array;
	}
	
	
	public function testimportAction($destiny, $list, $posCol, $delimiter)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$log = $this->logger;
		
		$hora = time();
		$ipaddress = ip2long($_SERVER["REMOTE_ADDR"]);
		
		$open = fopen($destiny, "r");
		
		$cont = 0;
		$values = "";
		$posEmail = $posCol[0];
		$posName = $posCol[1];
		$posLastname = $posCol[2];
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			if (!empty($linew)) {
				if($cont != 0) {
					$values.=", ";
				}
				list($user, $edomain) = preg_split("/@/", $linew[0], 2);	
				$values.= "('$linew[$posEmail] ', '$linew[$posName]', '$linew[$posLastname]', '$edomain')";
				$cont++;
			}
		}
		$tabletmp = "INSERT INTO tmpimport(email, name, lastName, domain) VALUES ".$values.";";
		
		$findidcontact = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail AND c.idDbase = $list->idDbase) SET t.idContact = c.idContact, t.status = 'BD' WHERE t.idEmail IS NOT NULL;";
		
		$findcoxcl = "UPDATE tmpimport t JOIN coxcl x ON (t.idContact = x.idContact AND x.idContactlist = $list->idContactlist) SET t.coxcl = 1, t.status = 'Lista' WHERE t.idContact IS NOT NULL;";
		
		$extraQuery = "UPDATE tmpimport t JOIN email e ON (t.email = e.email AND e.idAccount = 6) SET t.idEmail = e.idEmail;
				UPDATE tmpimport t JOIN domain d ON (t.domain = d.name) SET t.idDomain = d.idDomain WHERE t.idEmail IS NOT NULL;";
		
		$createcontacts = "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) SELECT $list->idDbase, t.idEmail, t.name, t.lastName, $hora, 0, 0, 0, $ipaddress, $ipaddress, $hora, $hora, $hora FROM tmpimport t WHERE t.idContact IS NULL;";
		
		$createcoxcl = "INSERT INTO coxcl (idContactlist, idContact, createdon) SELECT $list->idContactlist, t.idContact, $hora FROM tmpimport t WHERE t.coxcl IS NULL";
		
		$db->begin();
		
		$firstquery = $db->execute($tabletmp);
		
		$extraEmailDomain = $db->execute($extraQuery);
		
		$idcontacts = $db->execute($findidcontact);
		$contactscreated = $db->execute($createcontacts);
		$idcontactscreated = $db->execute($findidcontact);
		
		$coxcls = $db->execute($findcoxcl);
		$createdcoxcl = $db->execute($createcoxcl);
		
		$db->commit();
	}
}