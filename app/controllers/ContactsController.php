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
		
		$contents = $this->request->getpost('arraybatch');
		
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
		
		$_SESSION['batch'] = $batchreal;

		$this->view->setVar("batch", $batch);	
		$this->view->setVar("idContactlist", $idContactlist);
		
	}
	
	public function importbatchAction($idContactlist)
	{
		$log = $this->logger;
		
		$list = Contactlist::findFirstByIdContactlist($idContactlist);
		$batch = $_SESSION['batch'];
		
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
			$newcontact->is_subscribed = "";
			$newcontact->is_spam = "";
			$newcontact->is_active = 1;
			
			try {
				$contact = $wrapper->searchContactinDbase($newcontact->email);
				if(!$contact) {
					$contact = $wrapper->createNewContactFromJsonData($newcontact);
				}
			}
			catch (\InvalidArgumentException $e) {
				$log->log('Exception: [' . $e . ']');
			}
			catch (\Exception $e) {
				$log->log('Exception: [' . $e . ']');
			}				
		}
//			$this->flashSession->success('Contactos creados exitosamente');
			$this->response->redirect("contactlist/show/$list->idContactlist#/contacts");
	}
	
	protected function validateImportedFile($file) 
	{
		$extensions = array ('csv');
		$maxSizeFile = 8388608; //Máximo tamaño del archivo en bytes
		
		$this->objFile = $file;
		$fileName = strtolower($this->objFile['name']);
		
		if(!in_array(end(explode('.', $fileName)), $extensions)) {
			return false;
		}
		
		else if ($this->objFile['size'] > $maxSizeFile) {
			return false;
		}
		
		else {
			return true;
		}
		
		
	}
	public function importAction()
	{
		$account = $this->user->account->idAccount ;
		$idContactlist = $this->request->getpost('idcontactlist');
		$idDbase = $this->request->getpost('database');
		
		if (empty($_FILES['importFile']['name'])) {
			$this->flashSession->error("No ha enviado ningún archivo");
			$this->response->redirect("contactlist/show/$idContactlist#/contacts/import");
		}
		
		else {
			
			$validate = $this->validateImportedFile($_FILES['importFile']);
			
			if ($validate == false) {
				$this->flashSession->error("Error en el archivo");
				$this->response->redirect("");
			}
			else {
				$internalNumber = uniqid();
				$date = date("ymdHi",time());
				$separator = "\r\n";
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
								$this->response->redirect("contactlist/show$idContactlist#/contacts/import");
						}
				}
				else {
						$destiny =  "C:\\".$internalName;
						copy($_FILES['importFile']['tmp_name'],$destiny);

						$open = fopen($destiny, "r");
						$data = array();

						$line = fgets($open);
								$eachdata = explode($separator, trim($line));
								$data['row1'] = $eachdata[0];
								$data['row2'] = $eachdata[0];
								$data['row3'] = $eachdata[0];
						fclose($open);

						$customfields = Customfield::findByIdDbase($idDbase);

						$this->view->setVar("customfields", $customfields);
						$this->view->setVar("row", $data);
				}
			}
			
		}
		
			
	}
			
}
