<?php
class ContactsController extends ControllerBase
{
	
	public function indexAction() 
	{
		
	}
	
	public function newbatchAction($idList)
	{
		$this->flashSession->error('');
		
		$list = Contactlist::findFirstByIdList($idList);

		$db = Dbase::findFirstByIdDbase($list->idDbase);

		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$contents = $this->request->getpost('arraybatch');
		
		if (empty($contents)) {
			$this->flashSession->error('No hay valores en el campo');
			$this->response->redirect("contactlist/show/$idList#/contacts/newbatch");
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
		$this->view->setVar("idList", $idList);
		
	}
	
	public function importbatchAction($idList)
	{
		$log = $this->logger;
		
		$list = Contactlist::findFirstByIdList($idList);
		$batch = $_SESSION['batch'];
		
		foreach ($batch as $batchC) {
		// Crear el nuevo contacto:
			
			$wrapper = new ContactWrapper();
			$wrapper->setAccount($this->user->account);
			$wrapper->setIdDbase($list->idDbase);
			$wrapper->setIdList($idList);
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
			$this->response->redirect("contactlist/show/$list->idList#/contacts");
	}
	
	public function importAction()
	{
		$file = $_FILES['importfile'];
        $fileInfo = pathinfo($file['name']);
		$separator = $this->request->getpost('separator');
		$idDbase = $this->request->getpost('database');
		
		$destiny =  "C:\\".$fileInfo['basename'];
		copy($_FILES['importfile']['tmp_name'],$destiny);
		
		$open = fopen($destiny, "r");
		$data = array();
		
			$line = fgets($open);
			$eachdata = explode($separator, trim($line));
			$data['col1'] = $eachdata[0];
			$data['col2'] = $eachdata[1];
			$data['col3'] = $eachdata[2];

		fclose($open);
		$customfields = Customfield::findByIdDbase($idDbase);
		
		$this->view->setVar("customfields", $customfields);
		$this->view->setVar("row", $data);
		
	}
}