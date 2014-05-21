<?php
class ContactsController extends ControllerBase
{
	
	public function indexAction() 
	{
		
	}
	
	public function searchAction()
	{
		
	}

	public function newbatchAction($idContactlist)
	{
		$log = $this->logger;

		$this->flashSession->error('');
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);

		$db = Dbase::findFirstByIdDbase($list->idDbase);

		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$contents = $this->request->getPost('arraybatch');
		
		if (empty($contents)) {
			$this->flashSession->error('No hay valores en el campo');
			$this->response->redirect("contactlist/show/$idContactlist#/contacts/newbatch");
		}
		
		$eachrow = explode("\n", $contents);

		$emailsToFind = array();
		/*
		 * =================================================================================
		 * NOTA
		 * Modifique este codigo, mejorando la lectura y rapidez en validacion de repetidos
		 * de repetidos
		 * =================================================================================
		 */
		foreach ($eachrow as $e) {
			$eachdata = explode(",", trim($e));

			if(isset($eachdata[0]) && !empty($eachdata[0])) {
				$email = $eachdata[0];
				$name = (isset($eachdata[1]))?trim($eachdata[1]):'';
				$last_name = (isset($eachdata[2]))?trim($eachdata[2]):'';
				$birth_date = (isset($eachdata[3]))?trim($eachdata[3]):'';
				
				// Evitar duplicados
				if (!isset($emailsToFind[$email])) {
					$batchreal[] = array(
						'email' => $email,
						'name' => $name,
						'lastName' => $last_name,
						'birthDate' => $birth_date
					);
					$emailsToFind[$email] = true;
				}
			}
		}
		
		/*
		 * =================================================================================
		 * NOTA
		 * Actualice esta parte, unificando el proceso de creacion de los contactos
		 * en una sola accion.
		 * REVISAR!!!
		 * =================================================================================
		 */
		$contactsAdded = array();
		$contactsCreated = array();
		$contactsErrors = array();
		
		$dateFormat = new \EmailMarketing\General\Misc\DateFormat();
		
		foreach ($batchreal as $batchC) {
			// Crear el nuevo contacto:
			$wrapper = new ContactWrapper();
			$wrapper->setAccount($this->user->account);
			$wrapper->setDateFormat($dateFormat);
			$wrapper->setIdDbase($list->idDbase);
			$wrapper->setIdContactlist($idContactlist);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		

			$newcontact = new stdClass();
		
			$newcontact->email = $batchC['email'];
			$newcontact->name = $batchC['name'];
			$newcontact->lastName = $batchC['lastName'];
			
			$date = explode('/', $batchC['birthDate']);
			if (count($date) != 0) {
				$newDate = (checkdate($date[1],$date[0],$date[2]) ? $batchC['birthDate'] : null);
			}
			else {
				$newDate = null;
			}
			
			$newcontact->birthDate = $newDate;
			$newcontact->status = "";
			$newcontact->activatedOn = "";
			$newcontact->bouncedOn = "";
			$newcontact->subscribedOn = "";
			$newcontact->unsubscribedOn = "";
			$newcontact->spamOn = "";
			$newcontact->ipActive = "";
			$newcontact->ipSubscribed = "";
			$newcontact->updatedOn = "";
			$newcontact->createdOn = "";
			$newcontact->isBounced = "";
			$newcontact->isSubscribed = 1;
			$newcontact->isSpam = "";
			$newcontact->isActive = 1;


			$info = $batchC;
			$info['isValid'] = false;
			try {
				$contact = $wrapper->addExistingContactToListFromDbase($newcontact->email, $list);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($newcontact, $list);
					$info['status'] = 'Nuevo contacto creado';
					$info['isValid'] = true;
					$contactsCreated[] = $info;
				}
				else {
					$info['status'] = 'Contacto existente adicionado a la lista';
					$info['isValid'] = true;
					$contactsCreated[] = $info;
				}
			}
			catch (\InvalidArgumentException $e) {
				$this->traceFail("Error adding contacts by newbatch, idContactlist: {$idContactlist}");
				$log->log('Exception: [' . $e . ']');
				$info['status'] = $e->getMessage();
				$contactsErrors[] = $info;
			}
			catch (\Exception $e) {
				$this->traceFail("Error adding contacts by newbatch, idContactlist: {$idContactlist}");
				$info['status'] = 'Error general -- contacte al administrador';
				$contactsErrors[] = $info;
				$log->log('Exception: [' . $e . ']');
			}
		}

		$totalValidContacts = count($contactsCreated) + count($contactsAdded);
		$this->traceSuccess("{$totalValidContacts} contacts added by newbatch, idContactlist: {$idContactlist}");
		$this->view->setVar("account", $this->user->account);
		$this->view->setVar("total", $totalValidContacts);
		$this->view->setVar("errors", count($contactsErrors));
		$this->view->setVar("batch", array_merge($contactsCreated, $contactsAdded, $contactsErrors));
		$this->view->setVar("idContactlist", $idContactlist);
		$this->view->currentActiveContacts = $this->user->account->countActiveContactsInAccount();
		
	}
	
	public function importbatchAction($idContactlist)
	{
		
		$log = $this->logger;
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		if (!$this->session->has('batch_data')) {
			return $this->response->redirect('contactlist/show/$list->idContactlist#/contacts');
		}
		$batch = $this->session->get('batch_data');
		
		foreach ($batch as $batchC) {
			// Crear el nuevo contacto:
			$wrapper = new ContactWrapper();
			$wrapper->setAccount($this->user->account);
			$wrapper->setIdDbase($list->idDbase);
			$wrapper->setIdContactlist($idContactlist);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		

			$newcontact = new stdClass();
			
			$newcontact->email = $batchC['email'];
			$newcontact->name = $batchC['name'];
			$newcontact->lastName = $batchC['lastName'];
			$newcontact->status = "";
			$newcontact->activatedOn = "";
			$newcontact->bouncedOn = "";
			$newcontact->subscribedOn = "";
			$newcontact->unsubscribedOn = "";
			$newcontact->spamOn = "";
			$newcontact->ipActive = "";
			$newcontact->ipSubscribed = "";
			$newcontact->updatedOn = "";
			$newcontact->createdOn = "";
			$newcontact->isBounced = "";
			$newcontact->isSubscribed = 1;
			$newcontact->isSpam = "";
			$newcontact->isActive = 1;
			
			try {
				$contact = $wrapper->addExistingContactToListFromDbase($newcontact->email, $list);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($newcontact, $list);
				}
			}
			catch (\InvalidArgumentException $e) {
				$log->log('Exception: [' . $e . ']');
			}
			catch (\Exception $e) {
				$log->log('Exception: [' . $e . ']');
			}
		}
		
		return $this->response->redirect("contactlist/show/$list->idContactlist#/contacts");
	}
	
	protected function validateImportedFile($file) 
	{
		$extensions = array('csv');
		$maxSizeFile = 8388608; //Máximo tamaño del archivo en bytes (8 mb)
		
		$fileName = strtolower($file['name']);
		$tmp = (explode('.', $fileName));
		$extension = end($tmp);

		if(!in_array($extension, $extensions, true)) {
			return $msg = "Ha enviado un tipo de archivo no soportado, recuerde que el tipo de archivo es .csv";
		}
		
		else if ($file['size'] > $maxSizeFile) {
			return $msg = "Ha sobrepasado el tamaño limite de archivo";
		}
		
		else {
			return $msg = "";
		}
		
		
	}
	public function importAction()
	{
		
		$account = $this->user->account->idAccount ;
		$idContactlist = $this->request->getPost('idcontactlist');
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		$idDbase = $list->idDbase;
		
		if (empty($_FILES['importFile']['name'])) {
			$this->flashSession->error("No ha enviado ningún archivo");
			return $this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
		}
		
		else {
			
			$validate = $this->validateImportedFile($_FILES['importFile']);
			
			if ($validate != NULL) {
				$this->flashSession->error($validate);
				return $this->response->redirect("contactlist/show/$idContactlist#/contacts/import");	
			}
			else {
				$internalNumber = uniqid();
				$date = date("ymdHi",time());
				$internalName = "{$account}_{$date}_{$internalNumber}.csv";

				$fileInfo = $_FILES['importFile']['name'];

				$saveDataFile = new Importfile();

				$saveDataFile->idAccount = $account;
				$saveDataFile->internalName = $internalName;
				$saveDataFile->originalName = $fileInfo;
				$saveDataFile->createdon = time();

				if (!$saveDataFile->save()) {
						foreach ($saveDataFile->getMessages() as $msg) {
							$this->flashSession->error($msg);
							$this->debug->log($msg);
						}
						return $this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
				}
				else {
					$destiny =  $this->tmppath->dir . $internalName;
					copy($_FILES['importFile']['tmp_name'],$destiny);

					$open = fopen($destiny, "r");
					$line = array('', '', '', '', '');
					for($i=0; $i<5 && !feof($open); $i++) {
						$l = trim(fgets($open));
						$line[$i] = str_replace('"', '\"', $l);
					}
					fclose($open);

					$customfields = Customfield::findByIdDbase($idDbase);

					$this->view->setVar("customfields", $customfields);
					$this->view->setVar("row", $line);		
					$this->view->setVar("idContactlist", $idContactlist);
					$this->view->setVar("idImportfile", $saveDataFile->idImportfile);
				}
			}
		}
	}
	
	public function processfileAction($idContactlist, $idImportfile)
	{
		$this->view->disable();
		$log = $this->logger;
		
		$file = Importfile::findFirstByIdImportfile($idImportfile);
		$nameFile = $file->internalName;
		$header = $this->request->getPost('header');
		$fields['email'] = $this->request->getPost('email');
		$fields['name'] = $this->request->getPost('name');
		$fields['lastname'] = $this->request->getPost('lastname');		
		$birthdate = $this->request->getPost('birthdate');
		$fields['birthdate'] = (empty($birthdate) ? 'd/m/Y' : $birthdate);
		$dateformat = $this->request->getPost('dateformat');
		$delimiter = $this->request->getPost('delimiter');
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		$customfields = Customfield::findByIdDbase($list->idDbase);
		
		foreach ($customfields as $field) {
			$namefield= "campo".$field->idCustomField;
			$fields[$field->idCustomField] = $this->request->getPost($namefield);
		}
		

		$destiny =  $this->tmppath->dir . $nameFile;
		$idAccount = $this->user->account->idAccount;
		$ipaddress = ip2long($_SERVER["REMOTE_ADDR"]);
		
		$open = fopen($this->tmppath->dir . $nameFile, "r");
		
		if(!$open) {
			$log->log('Error al abrir el archivo original');
			/*
			 * ===========================================================================
			 * ERROR
			 * No se esta controlando esta situacion!!!
			 * Cambie para que genere un error y redireccione
			 * ===========================================================================
			 */
			$this->flashSession->error('Error al procesar el archivo. Contacte a su administrador!');
			return $this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
		}
		
		if($header) {
			$linew = fgetcsv($open, 0, $delimiter);
		}
		
		$linecount = 0;
		
		while(!feof($open)){
			
			$linew = fgetcsv($open, 0, $delimiter);
			
			if ( !empty($linew) ) {
				$linecount++;
			}
		}
		
		$newprocess = new Importproccess();
						
		$newprocess->idAccount = $idAccount;
		$newprocess->inputFile = $idImportfile;
		$newprocess->status = "Pendiente";
		$newprocess->totalReg = $linecount;
		$newprocess->processLines = 0;
		
		if(!$newprocess->save()) {
			$log->log('No se creo ningun proceso de importaction');
			/*
			 * ===========================================================================
			 * ERROR
			 * Esto no debería generar una excepción porque esa excepcion se va
			 * al cliente!!!
			 * Cambie para que genere un error y redireccione
			 * ===========================================================================
			 */
//			throw new \InvalidArgumentException('No se creo ningun proceso de importaction');
			$this->flashSession->error('Error al procesar el archivo de importacion. Contacte a su administrador!');
			return $this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
		}		
		
		$arrayToSend = array(
			'fields' => $fields,
			'destiny' => $destiny,
			'delimiter' => $delimiter,
			'dateformat' => $dateformat,
			'header' => $header,
			'idContactlist' => $idContactlist,
			'idImportproccess' => $newprocess->idImportproccess,
			'idAccount' => $idAccount,
			'ipaddress' => $ipaddress
			);
		
		$toSend = json_encode($arrayToSend);
		
		try{
			$objcomm = new Communication(SocketConstants::getImportRequestsEndPointPeer());
			$objcomm->sendImportToParent($toSend, $newprocess->idImportproccess);
			$this->traceSuccess("{$linecount} contacts imported, idContactlist: {$idContactlist} / idImportFile: {$idImportfile}");
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			$this->traceFail("Error importing {$linecount} contacts, , idContactlist: {$idContactlist} / idImportFile: {$idImportfile}");
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
			$this->traceFail("Error importing {$linecount} contacts, , idContactlist: {$idContactlist} / idImportFile: {$idImportfile}");
		}
		
		return $this->response->redirect("process/import");
	}
	
	public function formAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('contacts/form', $parameters);
			list($idLink, $idContactlist, $idForm) = $idenfifiers;

			if ($this->request->isPost()) {
				$fields = $this->request->getPost();
				$contactlist = Contactlist::findFirst(array(
					'conditions' => 'idContactlist = ?1',
					'bind' => array(1 => $idContactlist)
				));
				
				$form = Form::findFirst(array(
					'conditions' => 'idForm = ?1',
					'bind' => array(1 => $idForm)
				));

				if( !$contactlist || !$form ) {
					return $this->response->redirect('error/link');
				}
				
				$wrapper = new ContactFormWrapper();
				$wrapper->setContactlist($contactlist);
				$wrapper->setForm($form);
				$wrapper->setAccount($contactlist->dbase->account);
				$wrapper->setIdDbase($contactlist->dbase->idDbase);
				$wrapper->setIdContactlist($contactlist->idContactlist);
				$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
				$wrapper->createContactFromForm($fields);
			}
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			if( !isset($form) && !$form) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($form->urlError, true);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			if( !isset($form) && !$form) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($form->urlError, true);
		}

		return $this->response->redirect($form->urlSuccess, true);
	}
	
	public function activateAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('contacts/activate', $parameters);
			list($idLink, $idContact, $idForm) = $idenfifiers;
			
			$form = Form::findFirst(array(
				'conditions' => 'idForm = ?1',
				'bind' => array(1 => $idForm)
			));
			
			$contact = Contact::findFirst(array(
				'conditions' => 'idContact = ?1',
				'bind' => array(1 => $idContact)
			));
			
			if( !$contact ) {
				if( !isset($form) && !$form) {
					return $this->response->redirect('error/link');
				}
				return $this->response->redirect($form->urlError, true);
			}
			
			$dbase = Dbase::findFirstByIdDbase($contact->idDbase);
			
			$wrapper = new ContactFormWrapper();
			$wrapper->setForm($form);
			$wrapper->setAccount($dbase->account);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
			$wrapper->activateContactFromForm($contact);
			
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			if( !isset($form) && !$form) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($form->urlError, true);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			if( !isset($form) && !$form) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($form->urlError, true);
		}
		
		return $this->response->redirect($form->urlSuccess, true);
	}
	
	public function updateAction($parameters)
	{
		try {
			$linkEncoder = new \EmailMarketing\General\Links\ParametersEncoder();
			$linkEncoder->setBaseUri(Phalcon\DI::getDefault()->get('urlManager')->getBaseUri(true));
			$idenfifiers = $linkEncoder->decodeLink('contacts/update', $parameters);
			list($idLink, $idContact, $idForm) = $idenfifiers;
			
			if ($this->request->isPost()) {
				$fields = $this->request->getPost();
				$form = Form::findFirst(array(
					'conditions' => 'idForm = ?1',
					'bind' => array(1 => $idForm)
				));
				
				if(!$form) {
					return $this->response->redirect('error/link');
				}

				$dbase = Dbase::findFirstByIdDbase($form->idDbase);

				$wrapper = new ContactFormWrapper();
				$wrapper->setForm($form);
				$wrapper->setAccount($dbase->account);
				$wrapper->setIdDbase($dbase->idDbase);
				$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
				$wrapper->updateContactFromForm($idContact, $fields);
			}
		}
		catch (\InvalidArgumentException $e) {
			$this->logger->log('Exception: [' . $e->getMessage() . ']');
			if( !isset($form) && !$form) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($form->urlError, true);
		}
		catch (\Exception $e) {
			$this->logger->log('Exception: [' . $e . ']');
			if( !isset($form) && !$form) {
				return $this->response->redirect('error/link');
			}
			return $this->response->redirect($form->urlError, true);
		}
		return $this->response->redirect($form->urlSuccess, true);
	}
}
