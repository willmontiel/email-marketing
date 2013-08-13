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
		
		$log = $this->logger;
		
		$contents = $this->request->getpost('arraybatch');
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		// Crear el nuevo contacto:
			$eachrow = explode("\n", $contents);
			$sizearray = count($eachrow);
			
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
			
			for($i=0; $i<$sizearray; $i++){
				$eachdata = explode(",", $eachrow[$i]);
				$newcontact->email = $eachdata[0];
				$newcontact->name = $eachdata[0];
				$newcontact->last_name = $eachdata[0];
				
				try {
				$contact = $wrapper->createNewContactFromJsonData($newcontact);
				}
				catch (\Exception $e) {
					$log->log('Exception: [' . $e . ']');
					return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating new contact!');	
				}
			}		
			$this->response->redirect("dbase/show/$idDbase#/contacts");
	}
}