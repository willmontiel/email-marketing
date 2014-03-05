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

	public function aperturasAction()
	{
		
	}
	public function indexAction()
	{

	}
	
	public function assettestAction()
	{
		$asset = new Asset();
		$asset->idAccount = 14;
		$asset->fileName = 'lala.png';
		$asset->fileSize = 122345;
		$asset->dimensions = '100x100';
		$asset->type = 'image/png';
		$asset->createdon = 123456789;
		
		if (!$asset->save()) {
			$this->logger->log('Error');
		}
		
	}
	public function testzmqAction()
	{
		$this->view->disable();
		$context = new ZMQContext();
		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$requester->connect("tcp://localhost:5556");
		$toSend = 'Test de Prueba';
		$requester->send($toSend);
		return $this->response->setContent("Se fue el pedido");

	}
	public function testtransactionAction()
	{
		
		try {
			$this->db->begin();
			$contact = new Contact();


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
				$this->db->rollback();
			}

			$associate = new Coxcl();

			$associate->idContactlist = 1;
			$associate->contact = $contact;
			$t = '';

			if(!$associate->save()) {
				foreach ($associate->getMessages() as $m) {
					$t = $m->getMessage() . '<br/>';
				}
				$this->db->rollback();
			}
			$this->db->commit();

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
	
	
	public function testimportAction($destiny, $list, $posCol, $delimiter, $account, $idImportproccess)
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$log = $this->logger;
		
		$hora = time();
		$ipaddress = ip2long($_SERVER["REMOTE_ADDR"]);
		
		$open = fopen($destiny, "r");
		
		$firstline = TRUE;
		$values = "";
		$contactLimit = $account->contactLimit;
		$activeContacts = $account->countActiveContactsInAccount();
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			$name = (isset($posCol[1]))?$linew[$posCol[1]]:"";
			$lastname = (isset($posCol[2]))?$linew[$posCol[2]]:"";
			
			if ( !empty($linew) ) {
				if ( ($activeContacts <= $contactLimit) ) {
					$email = strtolower($linew[0]);
					if ( \filter_var($email, FILTER_VALIDATE_EMAIL) ) {
						if ( !$firstline ) {
							$values.=", ";
						}
						list($user, $edomain) = preg_split("/@/", $linew[0], 2);	
						$values.= "('$linew[0]', find_or_create_email('$linew[0]', '$edomain', $account->idAccount), '$name', '$lastname', '$edomain', find_domain('$edomain'))";
						$firstline = FALSE;
					}
				}
			}
		}
		
		$tabletmp = "INSERT INTO tmpimport(email, idEmail, name, lastName, domain, idDomain) VALUES ".$values.";";
		
		$findidemailblocked = "UPDATE tmpimport t JOIN email e ON (t.idEmail = e.idEmail AND e.idAccount = $account->idAccount) SET t.blocked = 1, t.status = 'Blq' WHERE t.idEmail IS NOT NULL AND e.blocked > 0;";
		
		$findidcontact = "UPDATE tmpimport t JOIN contact c ON (t.idEmail = c.idEmail AND c.idDbase = $list->idDbase) SET t.idContact = c.idContact WHERE t.idEmail IS NOT NULL;";
		
		$findcoxcl = "UPDATE tmpimport t JOIN coxcl x ON (t.idContact = x.idContact AND x.idContactlist = $list->idContactlist) SET t.coxcl = 1, t.status = 'Lst' WHERE t.idContact IS NOT NULL;";
		
		$createcontacts = "INSERT INTO contact (idDbase, idEmail, name, lastName, status, unsubscribed, bounced, spam, ipActivated, ipSubscribed, createdon, subscribedon, updatedon) SELECT $list->idDbase, t.idEmail, t.name, t.lastName, $hora, 0, 0, 0, $ipaddress, $ipaddress, $hora, $hora, $hora FROM tmpimport t WHERE t.idContact IS NULL AND t.blocked IS NULL;";
		
		$createcoxcl = "INSERT INTO coxcl (idContactlist, idContact, createdon) SELECT $list->idContactlist, t.idContact, $hora FROM tmpimport t WHERE t.coxcl IS NULL";
		
		$db->begin();
		
		$firstquery = $db->execute($tabletmp);
		
		$emailsblocked = $db->execute($findidemailblocked);
		
		$idcontacts = $db->execute($findidcontact);
		$contactscreated = $db->execute($createcontacts);
		$idcontactscreated = $db->execute($findidcontact);
		
		$coxcls = $db->execute($findcoxcl);
		$createdcoxcl = $db->execute($createcoxcl);
		
		$db->commit();
		
		$this->runReports($destiny, $delimiter, $activeContacts, $contactLimit, $idImportproccess);
		
	}
	
	protected function runReports($destiny, $delimiter, $activeContacts, $contactLimit, $idImportproccess) {
		
		$db = Phalcon\DI::getDefault()->get('db');
		$total = 0;
		$invalid = 0;
		$limit = 0;
		$success = array();
		$errors = array();
		
		$query1 = "SELECT t.email FROM tmpimport t WHERE t.blocked = 1";
		//$query2 = "SELECT t.email FROM tmpimport t WHERE t.coxcl = 1 AND t.blocked IS NULL";
		
		$db->begin();
		
		$emailsBlocked = $db->execute($query1);
		//$emailsRepeated = $db->execute($query2);
		
		$db->commit();
		
		$open = fopen($destiny, "r");
		
		while(! feof($open)) {
			$linew = fgetcsv($open, 0, $delimiter);
			array_push($success, $linew);
			
			if ( !empty($linew) ) {
				if ( !($activeContacts <= $contactLimit) ) {
					array_pop($success);
					array_push($linew, "Limite de Contactos Excedido");
					array_push($errors, $linew);
					$limit++;
				}
				else {
					$email = strtolower($linew[0]);
					if ( !\filter_var($email, FILTER_VALIDATE_EMAIL) ) {
						array_pop($success);
						array_push($linew, "Correo Invalido");
						array_push($errors, $linew);
						$invalid++;
					} 
					else {
						foreach ($emailsBlocked as $emailblocked) {
							if($emailblocked == $linew[0]) {
								array_pop($success);
								array_push($linew, "Correo Bloqueado");
								array_push($errors, $linew);
								$bloqued++;
							}
						}
//						foreach ($emailsRepeated as $emailrepeated) {
//							if($emailrepeated == $linew[0]){
//								array_pop($success);
//								array_push($linew, "Existente");
//								array_push($errors, $linew);
//								$exist++;
//							} 
//						}
						$total++;
					}
				}
			}
		}
		$this->createReports($errors, $success, $idImportproccess);
	}
	
	protected function createReports($errors, $success, $idImportproccess)
	{
		$uniquecode = uniqid();
		
		$nameNimported = $this->account->idAccount."_".date("ymdHi",time())."_".$uniquecode."noneimported.csv";
		
		$saveFileError = new Importfile();
		$saveFileError->idAccount = $this->account->idAccount;
		$saveFileError->internalName = $nameNimported;
		$saveFileError->originalName = $nameNimported;
		$saveFileError->createdon = time();

		if (!$saveFileError->save()) {
				foreach ($saveFileError->getMessages() as $msg) {
						$this->flashSession->error($msg);
						$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
				}
		} else {
			$fp = fopen('../tmp/ifiles/' . $nameNimported, 'w');

			foreach ($errors as $error) {
				fputcsv($fp, $error);
			}

			fclose($fp);
		}
		
		$nameImported = $this->account->idAccount."_".date("ymdHi",time())."_".$uniquecode."imported.csv";
		
		$saveFileSuccess = new Importfile();
		$saveFileSuccess->idAccount = $this->account->idAccount;
		$saveFileSuccess->internalName = $nameImported;
		$saveFileSuccess->originalName = $nameImported;
		$saveFileSuccess->createdon = time();
		
		if (!$saveFileSuccess->save()) {
				foreach ($saveFileSuccess->getMessages() as $msg) {
						$this->flashSession->error($msg);
						$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
				}
		} else {
			$fp = fopen('../tmp/ifiles/' . $nameImported, 'w');		

			foreach ($success as $succ) {
				fputcsv($fp, $succ);
			}

			fclose($fp);
		}
		
		$proccess = Importproccess::findFirstByIdImportproccess($idImportproccess);
		
		$proccess->errorFile = $saveFileError->idImportfile;
		$proccess->successFile = $saveFileSuccess->idImportfile;
		
		if(!$proccess->save()) {
			throw new InvalidArgumentException("Error al crear el registro del proceso de importacion");
		}
		
	}
	
	public function transactionsegmentAction()
	{
		$log = $this->logger;
		
		$segment = new Segment();

		$segment->idDbase = 7;
		$segment->name = "Segmento de pruebas";
		$segment->description = "Segmento de prueba para transacciones";
		$segment->criterion = "any";
		$segment->createdon = time();

		$c = array();
		
		$criteria = new Criteria();

		$criteria->idCustomField = null;
		$criteria->relation = 'equals';
		$criteria->value = 'abcd';
		$criteria->fieldName = 'email';
		$criteria->type = 'email';
		
		$c[] = $criteria;

		$criteria = new Criteria();
		$criteria->idCustomField = null;
		$criteria->relation = 'equals';
		$criteria->value = 'efgh';
		$criteria->fieldName = 'name';
		$criteria->type = 'contact';
		
		$c[] = $criteria;
		
		$criteria = new Criteria();
		$criteria->idCustomField = 123456;
		$criteria->relation = 'equals';
		$criteria->value = 'efgh';
		$criteria->fieldName = 'name';
		$criteria->type = 'contact';
		
		$c[] = $criteria;
		
		$segment->criteria = $c;
		try {		
			if (!$segment->save()) {
				$txt = '';
				foreach ($segment->getMessages() as $msg) {
					$txt .= '. ' . $msg . PHP_EOL;
				}
				$log->log("error: [$txt]\n");
			}
		}
		catch (Exception $e) {
			$log->log("error: [$e]\n");
		}
	}
	
	function testemailcontactAction()
	{
		$log = $this->logger;
		$account = $this->user->account;
		
		$txt = array();
		
		$txt[] = "Account: [{$account->companyName} - {$account->idAccount}]";
		$db = $account->dbases[0];
		$txt[] = "Dbase: [{$db->name} - {$db->idDbase}]";
		
		$email = $this->addEmail($account);
		
		$txt[] = "Email1: [{$email->email} - {$email->idEmail}]";
		
		$contact = new Contact();
		
		$contact->idDbase = $db->idDbase;
		$contact->idEmail = $email->idEmail;
		$contact->name = 'Testing contact 001';
		$contact->lastName = 'test1';
		$contact->bounced = 0;
		$contact->unsubscribed = 0;
		$contact->spam = 0;
		$contact->ipActivated = 0;
		$contact->ipSubscribed = 0;
		$contact->updatedon = time();
		$contact->subscribedon = time();
		$contact->status = time();
		$contact->createdon = time();
		
		if (!$contact->save()) {
			foreach ($contact->getMessages() as $msg) {
				$log->log('Error: ' . $msg);
			}
			$log->log('Error while creating contact!');
			return false;
		}
		
		$txt[] = "Contact: [{$contact->idDbase} - {$contact->idContact}], [{$contact->name} - {$contact->lastName}]";
		
		$email2 = $this->addEmail($account);
		
		$txt[] = "Email2: [{$email2->email} - {$email2->idEmail}]";
		
		$contact->email = $email2;
		
		if (!$contact->save()){
			foreach ($contact->getMessages() as $msg) {
				$log->log('Error2: ' . $msg);
			}
			$log->log('Error while creating contact2!');
			return false;
		}
		
		$log->log('Txt: ' . print_r($txt, true));
	}
	
	
	protected function addEmail($account)
	{
		$x = rand(5, 15);
		$log = $this->logger;
		$email = new Email;
		
		$email->idAccount = $account->idAccount;
		$email->idDomain = 88;
		$email->email = 'contact' . $x . '@test.com';
		$email->bounced = 0;
		$email->spam = 0;
		$email->blocked = 0;
		$email->createdOn = time();
		
		if (!$email->save()) {
			foreach ($email->getMessages() as $msg) {
				$log->log('Error: ' . $msg);
			}
			$log->log('Error while creating email!');
			return false;
		}
		return $email;
	}
	
	public function startAction($idMail)
	{
		$log = $this->logger;
		
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		if ($mail) {
			$dbases = Dbase::findByIdAccount($account->idAccount);
			
			$id = array();
			foreach ($dbases as $dbase) {
				$id[] = $dbase->idDbase;
			}
			
			$idDbases = implode(', ', $id);
			
			try {
				$identifyTarget = new IdentifyTarget();
				$identifyTarget->identifyTarget($mail);
			
				$prepareMail = new PrepareContentMail($account);
				$content = $prepareMail->getContentMail($mail);
				
				$mailField = new MailField($content->html, $content->text, $mail->subject, $idDbases);
				$cf = $mailField->getCustomFields();
				
				switch ($cf) {
					case 'No Fields':
						$customFields = false;
						$fields = false;
						break;
					case 'No Custom':
						$fields = true;
						$customFields = false;
						break;
					default:
						$fields = true;
						$customFields = $cf;
						break;
				}
				
				$log->log("customfield {$customFields}");
				$contactIterator = new ContactIterator($mail, $customFields);
//				$i = 0;
				foreach ($contactIterator as $contact) {
					if ($fields) {
						$c = $mailField->processCustomFields($contact);
//						$log->log("Html: " . $c['html']);
	//					$log->log("Text: " . $c['text']);
	//					$log->log("Subject: " . $c['subject']);
					}
//					$log->log("Contact: " . print_r($contact, true));
//					$i++;
				}
//				$log->log("Finalice! {$i} iteraciones");
			}
			catch (InvalidArgumentException $e) {

			}
		}
		
	}
	
	
	public function mailerAction()
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
		
		$this->asset = $di['asset'];
		$this->mta = $di['mtadata'];
		
		echo $this->asset->url;
		echo $this->mta->domain;
	}
	
	protected function stripAccents($cadena){
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		
		$cadena = utf8_decode($cadena);
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		$cadena = strtoupper($cadena);
		
		return utf8_encode($cadena);
	}
	
	public function transactionAction()
	{
		$TestObj = new TestObj();
		$TestObj->save();
	}

	public function facebooktestAction()
	{
		$log = $this->logger;
		
		// Create our Application instance (replace this with your appId and secret).
		$facebook = new Facebook(array(
		  'appId'  => '706764282697191',
		  'secret' => '969bfd05a58af3e68f76edd0548c6884',
		));

		// Get User ID
		$user = $facebook->getUser();
		
		// We may or may not have this data based on whether the user is logged in.
		//
		// If we have a $user id here, it means we know the user is logged into
		// Facebook, but we don't know if the access token is valid. An access
		// token is invalid if the user logged out of Facebook.

		if ($user) {
		  try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me');
			$log->log('Id de contacto es: ' . $user_profile['id'] . ' y token access es: ' . $facebook->getAccessToken());
			$this->view->setVar("user_profile", $user_profile);
			//Ver la informacion del usuario
			$log->log(print_r($facebook->api('/'.$user_profile['id']), true));
			//Ver la informacion de las Fan Pages que estan relacionadas con el usuario
			$log->log(print_r($facebook->api('/'.$user_profile['id'].'/accounts'), true));
		  } catch (FacebookApiException $e) {
			$log->log($e->getMessage());
			$user = null;
		  }
		}
		$this->view->setVar("user", $user);
		// Login or logout url will be needed depending on current user state.
		if ($user) {
		  $logoutUrl = $facebook->getLogoutUrl(array( 'next' => 'http://localhost/emarketing/' ));
		  $this->view->setVar("logoutUrl", $logoutUrl);
		} else {
		  $statusUrl = $facebook->getLoginStatusUrl();
//		  $loginUrl = $facebook->getLoginUrl(array('scope' => 'manage_pages, publish_stream', 'redirect_uri' => 'http://localhost/emarketing/'));
		  $loginUrl = $facebook->getLoginUrl(array('scope' => 'manage_pages, publish_stream'));
		  $this->view->setVar("statusUrl", $statusUrl);
		  $this->view->setVar("loginUrl", $loginUrl);
		}

		// This call will always work since we are fetching public data.
		$naitik = $facebook->api('/naitik');
		$this->view->setVar("naitik", $naitik);
		$log->log(print_r($_SESSION, true));
	}
	
	public function facebookpostingAction()
	{
		$log = $this->logger;
		$userid = '601962656546574';
		$access_token = 'CAAKCzGIClecBAD2ipXQVKUyjMT9lFTwz58eAdZCizc0pAtrRNRL4ZAAu38xraxLLFnzCp89wRvD05LEBtKeUiJu2hc6YzZByyeYkAQLbxKfFZAjHaxKr91fqLq9cwurFqnp0M1SWuWQe7B1s8EV20TV3PwWw6nn6MMBmIYXsB7GhGUnkFBOTsTxA27QmOEUZD';
		$facebook = new Facebook(array(
		  'appId'  => '706764282697191',
		  'secret' => '969bfd05a58af3e68f76edd0548c6884',
		));
		//If we have $user_profile we can share on his facebook wall
//		$log->log($facebook->getAccessToken());
		$params = array(
			"access_token" => $access_token, // see: https://developers.facebook.com/docs/facebook-login/access-tokens/
			"message" => "Esta es el post NUMERO 9 generado en la prueba de email marketing",
			"link" => "http://tstemail.sigmamovil.com/",
			"picture" => "http://i.imgur.com/lHkOsiH.png",
			"name" => "Prueba de Post en Facebook",
			"caption" => "www.tstemail.sigmamovil.com",
			"description" => "Este post de prueba NUMERO 9"
		  );

		  // post to Facebook
		  // see: https://developers.facebook.com/docs/reference/php/facebook-api/
		  try {
			$ret = $facebook->api('/'.$userid.'/feed', 'POST', $params);
			$log->log('Successfully posted to Facebook');
		  } catch(Exception $e) {
			echo $e->getMessage();
			$log->log('No publico');
			$log->log($e->getMessage());
		  }
		  
		  return $this->response->redirect('');
	}
	
	public function twittertestAction()
	{	
		$log = $this->logger;
//		$this->view->disable();
		$connection = new TwitterOAuth('YOwc7cROtCFjvftKgSOLDA', '4Y07b71bpIhLAgEvQqCF5c58NRcG807UiWGvaYXwKA');
		$temporary_credentials = $connection->getRequestToken("http://localhost/emarketing/test/twittertest");
		$redirect_url = $connection->getAuthorizeURL($temporary_credentials);
		$this->view->setVar("loginUrl", $redirect_url);
		if($_REQUEST['oauth_verifier']) {
			$connection = new TwitterOAuth('YOwc7cROtCFjvftKgSOLDA', '4Y07b71bpIhLAgEvQqCF5c58NRcG807UiWGvaYXwKA', $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
			$token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);
			$log->log(print_r($token_credentials, true));
			//las credenciales son las que se guardan en la base de datos
			$connection = new TwitterOAuth('YOwc7cROtCFjvftKgSOLDA', '4Y07b71bpIhLAgEvQqCF5c58NRcG807UiWGvaYXwKA', $token_credentials['oauth_token'], $token_credentials['oauth_token_secret']);
			
			$account = $connection->get('account/verify_credentials');
			$log->log(print_r($account, true));
			$status = $connection->post('statuses/update', array('status' => 'Text of status here y mas pa a donde'));
		}
	}
	
	public function imagetestAction()
	{
		$date = time();
		
		$mxc = Mxc::findFirst(array(
			'conditions' => 'idMail = ?1 AND idContact = ?2',
			'bind' => array(1 => 217,
							2 => 25441)
		));
		$this->logger->log('despues de mxc');
		$contact = $mxc->contact;
		
		$contact->bounced = $date;
		$contact->email->bounced = $date;
		
		$this->logger->log('agregando bounced');
		$dirtyObjects = array();
		$dirtyObjects[] = $contact;
		$dirtyObjects[] = $contact->email;
		$this->logger->log('agregando al objeto padre');
		
		$i = 1;
		foreach ($dirtyObjects as $object) {
			if (!$object->save()) {
				foreach ($object->getMessages() as $msg) {
					$this->logger->log('Error saving Object: ' . $msg);
				}
			}
			$this->logger->log('Se guardó el objeto: ' . $i);
			$i++;
		}
		$this->logger->log('Despues de guardar');
		unset($dirtyObjects);
		
		$this->logger->log('Obj Dirty destruido');
		
		$dbase = Dbase::findFirst(array(
			'conditions' => 'idDbase = ?1',
			'bind' => array(1 => $contact->idDbase)
		));
		
		$this->logger->log('Inicio actualización de contadores');
		$dbase->updateCountersInDbase();
		$this->logger->log('despues de la actualización de contadores');
		
		
		unset($dbase);
		
		try {
		$mxc = Mxc::findFirst(array(
			'conditions' => 'idMail = ?1 AND idContact = ?2',
			'bind' => array(1 => 217,
							2 => 25443)
		));
		}
		catch (Exception $e) {
			$this->logger->log('Error' . $e);
		}
		$this->logger->log('Depues de buscar otro mxc');
	}
	
	public function unsubscribedAction()
	{
		
	}
}