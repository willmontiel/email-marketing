<?php
class AddcontactsController extends ControllerBase
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
		
		$contents = $this->request->getPost("arraybatch");
		
		$wrapper = new ContactWrapper();
		$wrapper->setAccount($this->user->account);
		$wrapper->setIdDbase($idDbase);
		$wrapper->setIPAdress($_SERVER["REMOTE_ADDR"]);
		$log->log('Got IP address: ' . $_SERVER["REMOTE_ADDR"]);
		
		// Crear el nuevo contacto:
			$eachrow = explode("\n", $contents->arraybatch);
			$sizearray = count($eachrow);
			
			for($i=0; $i<$sizearray; $i++){
				$eachdata = explode(",", $eachrow[$i]);
				echo $eachdata[0];
				$contents->email = $eachdata[0];
				$contents->name = $eachdata[1];
				$contents->last_name = $eachdata[2];
				try {
				$contact = $wrapper->createNewContactFromJsonData($contents);
				}
				catch (\Exception $e) {
					$log->log('Exception: [' . $e . ']');
					return $this->setJsonResponse(array('status' => 'error'), 400, 'Error while creating new contact!');	
				}

			}
		$contactdata = $wrapper->convertContactToJson($contact);
		return $this->setJsonResponse(array('contact' => $contactdata), 201, 'Success');
		
	}
}