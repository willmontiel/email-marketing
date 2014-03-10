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
		$sizearray = count($eachrow);
		
		for($i=0; $i<$sizearray; $i++){
				$eachdata = explode(",", trim($eachrow[$i]));

				if(!empty($eachdata[0]))
				{
					$status = true;
					if(isset($eachdata[0]))	{
						
						$email = $eachdata[0];
						
					} else {						
						$status = false;

					}
					if(isset($eachdata[1]))	{
						
						$name = trim($eachdata[1]);
						
					} else {						
						$name = "";
						
					}
					if(isset($eachdata[2]))	{
						
						$last_name = trim($eachdata[2]);
						
					} else {
						$last_name = "";
						
					}
					
					if(isset($batch)){
						foreach ($batch as $b){
							if($b['email'] === $email) {
								$status=false;
							}
						}
					}
					
					$batch[] = array(
						'email' => $email,
						'name' => $name,
						'lastName' => $last_name,
						'status' => $status,
					);
					
					if($status) {
						$batchreal[] = array(
							'email' => $email,
							'name' => $name,
							'lastName' => $last_name,
						);
					}
				}
				
		}
		
		$this->session->set('batch_data', $batchreal);
			
		$totalValidContacts = count($batchreal);
		$this->view->setVar("account", $this->user->account);
		$this->view->setVar("total", $totalValidContacts);
		$this->view->setVar("batch", $batch);	
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
		$delimiter = $this->request->getPost('delimiter');
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		$customfields = Customfield::findByIdDbase($list->idDbase);
		
		foreach ($customfields as $field) {
			$namefield= "campo".$field->idCustomField;
			$fields[$field->idCustomField] = $this->request->getPost($namefield);
		}
		
		$destiny =  "../../../tmp/ifiles/".$nameFile;
		$idAccount = $this->user->account->idAccount;
		$ipaddress = ip2long($_SERVER["REMOTE_ADDR"]);
		
		$open = fopen("../tmp/ifiles/".$nameFile, "r");
		
		if(!$open) {
			$log->log('Error al abrir el archivo original');
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
		
		$newproccess = new Importproccess();
						
		$newproccess->idAccount = $idAccount;
		$newproccess->inputFile = $idImportfile;
		$newproccess->status = "Pendiente";
		$newproccess->totalReg = $linecount;
		$newproccess->processLines = 0;
		
		if(!$newproccess->save()) {
			$log->log('No se creo ningun proceso de importaction');
			throw new \InvalidArgumentException('No se creo ningun proceso de importaction');
		}		
		
		$arrayToSend = array(
			'fields' => $fields,
			'destiny' => $destiny,
			'delimiter' => $delimiter,
			'header' => $header,
			'idContactlist' => $idContactlist,
			'idImportproccess' => $newproccess->idImportproccess,
			'idAccount' => $idAccount,
			'ipaddress' => $ipaddress
			);
		
		$toSend = json_encode($arrayToSend);
		
		
		try{
			$context = new ZMQContext();
			$log->log('Contexto');
			$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
			$log->log('Socket');
			$requester->connect(SocketConstants::getImportClientProcessEndPoint());
			$log->log('Conexion');
			$requester->send($toSend);
			$log->log('Envio');
		}
		catch (\InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
		}
		catch (\Exception $e) {
			$log->log('Exception: [' . $e . ']');
		}
		
		return $this->response->redirect("proccess/show/$newproccess->idImportproccess");
	}
			
}
