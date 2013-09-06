<?php
class ContactsController extends ControllerBase
{
	
	public function indexAction() 
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
						'last_name' => $last_name,
						'status' => $status,
					);
					
					if($status) {
						$batchreal[] = array(
							'email' => $email,
							'name' => $name,
							'last_name' => $last_name,
						);
					}
				}
				
		}
		
		$this->session->set('batch_data', $batchreal);
			
		$totalValidContacts = count($batchreal);
		$this->view->setVar("limit", $this->user->account->contactLimit);
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
		
		$wrapper = new ContactWrapper();
		$wrapper->startCounter();
		
		foreach ($batch as $batchC) {
			// Crear el nuevo contacto:
			$wrapper->setAccount($this->user->account);
			$wrapper->setIdDbase($list->idDbase);
			$wrapper->setIdContactlist($idContactlist);
			$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		

			$newcontact = new stdClass();
			
			$newcontact->email = $batchC['email'];
			$newcontact->name = $batchC['name'];
			$newcontact->last_name = $batchC['last_name'];
			$newcontact->status = "";
			$newcontact->activated_on = "";
			$newcontact->bounced_on = "";
			$newcontact->subscribed_on = "";
			$newcontact->unsubscribed_on = "";
			$newcontact->spam_on = "";
			$newcontact->ip_active = "";
			$newcontact->ip_subscribed = "";
			$newcontact->updated_on = "";
			$newcontact->created_on = "";
			$newcontact->is_bounced = "";
			$newcontact->is_subscribed = 1;
			$newcontact->is_spam = "";
			$newcontact->is_active = 1;
			
			try {
				$contact = $wrapper->addExistingContactToListFromDbase($newcontact->email);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($newcontact);
				}
				//$wrapper->endCounters();
			}
			catch (\InvalidArgumentException $e) {
				$log->log('Exception: [' . $e . ']');
			}
			catch (\Exception $e) {
				$log->log('Exception: [' . $e . ']');
			}
			
			$wrapper->endCounters();
		}
		return $this->response->redirect("contactlist/show/$list->idContactlist#/contacts");
	}
	
	protected function validateImportedFile($file) 
	{
		$extensions = array('csv');
		$maxSizeFile = 8388608; //Máximo tamaño del archivo en bytes
		
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
			$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
		}
		
		else {
			
			$validate = $this->validateImportedFile($_FILES['importFile']);
			
			if ($validate != NULL) {
				$this->flashSession->error($validate);
				$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");	
			}
			else {
				$internalNumber = uniqid();
				$date = date("ymdHi",time());
				$internalName = $account."_".$date."_".$internalNumber.".csv";

				$fileInfo = $_FILES['importFile']['name'];

				$saveDataFile = new Importfile();

				$saveDataFile->idAccount = $account;
				$saveDataFile->internalName = $internalName;
				$saveDataFile->originalName = $fileInfo;
				$saveDataFile->createdon = time();

				if (!$saveDataFile->save()) {
						foreach ($saveDataFile->getMessages() as $msg) {
								$this->flashSession->error($msg);
								$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
						}
				}
				else {
						$newproccess = new Importproccess();
						
						$newproccess->idAccount = $account;
						$newproccess->inputFile = $saveDataFile->idImportfile;
						
						if(!$newproccess->save()) {
							foreach ($newproccess->getMessages() as $msg) {
								$this->flashSession->error($msg);
								$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
							}
						}
					
					
						$destiny =  "../tmp/ifiles/" . $internalName;
						copy($_FILES['importFile']['tmp_name'],$destiny);

						$open = fopen($destiny, "r");
						for($i=0; $i<5; $i++) {
							$line[$i] = trim(fgets($open));
						}
						fclose($open);

						$customfields = Customfield::findByIdDbase($idDbase);

						$this->view->setVar("customfields", $customfields);
						$this->view->setVar("row", $line);
						$this->view->setVar("idContactlist", $idContactlist);
						$this->view->setVar("idImportproccess", $newproccess->idImportproccess);
						
				}
			}
			
		}
		
			
	}
	
	public function processfileAction()
	{
		$log = $this->logger;
		
		$idImportproccess = $this->request->getPost('idImportproccess');
		$idContactlist = $this->request->getPost('idContactlist');
		
		$proccess = Importproccess::findFirstByIdImportproccess($idImportproccess);
		$file = Importfile::findFirstByIdImportfile($proccess->inputFile);
		$nameFile = $file->internalName;
		
		$fields[0] = $this->request->getPost('email');
		$fields[1] = $this->request->getPost('name');
		$fields[2] = $this->request->getPost('lastname');
		$delimiter = $this->request->getPost('delimiter');
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		$customfields = Customfield::findByIdDbase($list->idDbase);
		
		$numfield = 3;
		foreach ($customfields as $field) {
			$namefield= $field->name;
			$fields[$numfield] = $this->request->getPost(strtolower($namefield));
			$numfield++;
		}
				
		$destiny =  "../tmp/ifiles/".$nameFile;
		
		$importwrapper = new ImportContactWrapper();
		
		$importwrapper->setIdProccess($idImportproccess);
		$importwrapper->setIdContactlist($idContactlist);
		$importwrapper->setAccount($this->user->account);
		
		$count = $importwrapper->startImport($fields, $destiny, $delimiter);
		
		$this->view->setVar("count", $count);
	}
			
}
