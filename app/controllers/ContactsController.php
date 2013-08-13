<?php
class ContactsController extends ControllerBase
{
	
	public function indexAction() 
	{
		
	}
	
	public function newbatchAction($idDbase)
	{
		$db = Dbase::findFirstByIdDbase($idDbase);
		
		if (!$db || $db->account != $this->user->account) {
			return $this->setJsonResponse(array('status' => 'failed'), 404, 'No se encontro la base de datos');
		}
		
		$contents = $this->request->getpost('arraybatch');
		
		if (empty($contents)) {
			$this->flashSession->error('No hay valores en el campo');
			$this->response->redirect("dbase/show/$idDbase#/contacts/newbatch");
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
					
					$batch[] = array(
						'email' => $email,
						'name' => $name,
						'last_name' => $last_name,
						'status' => $status,
					);
				}
				
		}
		
		$jsbatch = json_encode($batch);
		$_SESSION['batch'] = $batch;
		
		$this->view->setVar("batch", $batch);	
		$this->view->setVar("idDbase", $idDbase);
		
	}
	
	public function importbatchAction($idDbase)
	{
		$log = $this->logger;

		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);		
		// Crear el nuevo contacto:
			$batch = $_SESSION['batch'];

			$newcontact = new stdClass();
			
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
			
			foreach ($batch as &$batch) {
				$newcontact->email = $batch['email'];
				$newcontact->name = $batch['name'];
				$newcontact->last_name = $batch['last_name'];

				try {
				$contact = $wrapper->createNewContactFromJsonData($newcontact);
				}
				catch (\Exception $e) {
					$log->log('Exception: [' . $e . ']');
					return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating new contact!');	
				}				
			}
			$this->flashSession->error('Contactos creados exitosamente');
			$this->response->redirect("dbase/show/$idDbase#/contacts");
	}
}