<?php
require_once "../app/library/swiftmailer/lib/swift_required.php";

class PdfmailController extends ControllerBase
{
	public $pdf_valid_names = array(
		1 => '/^[a-zA-Z]+_[0-9]+.*\.pdf$/',//NI_12345678.pdf
		2 => '/^[0-9]+.*\.pdf/',//11523458.pdf
		3 => '/^[a-zA-Z]+_[0-9]+_[a-zA-Z]+_[0-9]+.*\.pdf$/',//NI_12345678_CC_12345678.pdf
		4 => '/^[a-zA-Z]+[0-9]+.*\.pdf$/', //NI12345678.pdf
	);
	
	public function listAction()
	{
		
	}
	
	public function savemailAction($mails = null, $idMail = null)
	{
		$account = $this->user->account;
		$mail = null;
		$mailcontent = null;
	
		if ($idMail != null) {
			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1 AND idAccount = ?2',
				'bind' => array(1 => $idMail,
								2 => $account->idAccount)
			));
			
			if (!$mail) {
				return $this->setJsonResponse(array('error' => 'No se ha encontrado el correo por favor verifique la información'), 404, 'Mail not found!');
			}
			
			$mailcontent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $mail->idMail)
			));
		}
		
		if ($this->request->isPost() || $this->request->isPut()) {
			$contentsraw = $this->request->getRawBody();
			$contentsT = json_decode($contentsraw);
			$content = $contentsT->mail;
			
			$MailWrapper = new MailWrapper();
			$MailWrapper->setMail($mail);
			$MailWrapper->isPdf(true);
			$MailWrapper->setContent($content);
			$MailWrapper->setAccount($account);
			$MailWrapper->setSocialsKeys($this->fbapp->iduser, $this->fbapp->token, $this->twapp->iduser, $this->twapp->token);
			if ($mailcontent) {
				$MailWrapper->setMailContent($mailcontent);
			}
			
			try {	
				$MailWrapper->processDataForMail();
				$MailWrapper->saveMail();
				$response = $MailWrapper->getResponse();
				
				if ($idMail == null) {
					$this->traceSuccess("Create mail, idMail: {$response->data['id']}");
				}
				else {
					$this->traceSuccess("Update mail, idMail: {$idMail}");
				}
				
				return $this->setJsonResponse(array($response->key => $response->data), $response->code);
			}
			catch (InvalidArgumentException $e) {
				if ($idMail == null) {
					$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
				}
				else {
					$this->traceFail("Error update mail, idMail: {$idMail}");
				}
				
				$this->logger->log("InvalidArgumentException: {$e}");
				$response = $MailWrapper->getResponseMessageForEmber();
				return $this->setJsonResponse(array($response->key => $response->message), $response->code);
			}
			catch (Exception $e) {
				if ($idMail == null) {
					$this->traceFail("Error creating mail, USER: {$this->user->idUser}/{$this->user->username}");
				}
				else {
					$this->traceFail("Error update mail, idMail: {$idMail}");
				}
				
				$this->logger->log("Exception: {$e}");
				return $this->setJsonResponse(array('error' => 'Ha ocurrido un error contacte al administrador'), 500);
			}
		}	
		
		if ($mails != null) {
			$MailWrapper = new MailWrapper();
			$MailWrapper->setMail($mail);
			$MailWrapper->setAccount($account);
			$MailWrapper->setSocialsKeys($this->fbapp->iduser, $this->fbapp->token, $this->twapp->iduser, $this->twapp->token);
			$MailWrapper->setMailContent($mailcontent);
			$response = $MailWrapper->getResponse();
			return $this->setJsonResponse(array($response->key => $response->data), $response->code);
		}
	}
	
	public function composeAction($idMail = null)
	{
		$account = $this->user->account;
		
		$senders = Sender::find(array(
			'conditions' => 'idAccount = ?1',
			'bind' => array(1 => $account->idAccount)
		));
		
		if (count($senders) > 0) {
			$this->view->setVar('senders', $senders);
		}
		
		$this->view->setVar('account', $account);
		
		if($idMail != null) {
			$mail = Mail::findFirst(array(
				'conditions' => "idAccount = ?1 AND idMail = ?2 AND status = 'Draft' AND pdf = 1",
				'bind' => array(1 => $account->idAccount,
								2 => $idMail)
			));
			
			$mailcontent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			));
			
			if ($mailcontent) {
				switch ($mail->type) {
					case 'Html':
						$footerObj = new FooterObj();
						$footerObj->setAccount($this->user->account);
						$html = $footerObj->addFooterInHtml(html_entity_decode($mailcontent->content)); 
						break;

					case 'Editor':
						$editor = new HtmlObj();
						$editor->setAccount($this->user->account);
						$editor->assignContent(json_decode($mailcontent->content));
						$html = $editor->render();
						break;
				}
			}
			
			$this->view->setVar('mail', $mail);
		}
	}
	
	
	public function loadpdfAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => "idAccount = ?1 AND idMail = ?2 AND status = 'Draft' AND pdf = 1",
			'bind' => array(1 => $account->idAccount,
							2 => $idMail)
		));
		
		if (!$mail) {
			$this->response->redirect("error");
		}
		
		if ($this->request->isPost()) {
			if ($_FILES["file"]["error"]) {
				return $this->setJsonResponse(array(
					'error' => 'No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, contacte al administrador para más información')
					, 400 , 'Archivo vacio o incorrecto');
			}
			
			if (empty($_FILES['file']['name'])) {
				return $this->setJsonResponse(array(
					'error' => 'No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, por favor verifique la información')
					, 400 , 'Archivo vacio o incorrecto');
			}
			else {
				$data = new stdClass();
				$data->name = $_FILES['file']['name'];
				$data->size = $_FILES['file']['size'];
				$data->type = $_FILES['file']['type'];
				$data->tmp_dir = $_FILES['file']['tmp_name'];
				
				$ext = array('zip', 'ZIP');
				
				try {
					$uploader = new \EmailMarketing\General\Misc\Uploader();
					$uploader->setAccount($account);
					$uploader->setMail($mail);
					$uploader->setData($data);
					$uploader->validateExt($ext);
					$uploader->validateSize(512000);
					$uploader->uploadFile();
					
					$pdfmanager = new \EmailMarketing\General\Misc\PdfManager();
					$pdfmanager->setMail($mail);
					$pdfmanager->setSource($uploader->getSource());
					$pdfmanager->setDestination($uploader->getFolder());
					$pdfmanager->extract();
					$pdfmanager->save();
					$total = $pdfmanager->getTotal();
					
					return $this->setJsonResponse(array("success" => "Se han cargado {$total} archivo(s) exitosamente"), 200);
				} 
				catch (InvalidArgumentException $e) {
					$this->db->rollback();
					$this->logger->log("Exception: Error while uplodaing pdf {$e}");
					return $this->setJsonResponse(
						array('error' => $e->getMessage()), 400 , 'Error en archivo!');
				}
				catch (Exception $e) {
					$this->db->rollback();
					$this->logger->log("Exception: Error while uplodaing pdf {$e}");
					return $this->setJsonResponse(
						array('error' => "Ha ocurrido un error, por favor contacte al administrador"), 500 , 'Error interno!');
				}
			}
		}
		
		$this->view->setVar('mail', $mail);
	}
	
	public function structurenameAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => "idAccount = ?1 AND idMail = ?2 AND status = 'Draft' AND pdf = 1",
			'bind' => array(1 => $account->idAccount,
							2 => $idMail)
		));
		
		if (!$mail) {
			$this->response->redirect("error");
		}
		
		if ($this->request->isPost()) {
			$structure = $this->request->getPost("structure");
			$dir = "{$this->asset->dir}{$account->idAccount}/pdf/{$mail->idMail}/";
			
			$totalFiles = $this->getTotalFilesInFolder($dir);
			$files = $this->getFilesThatMatch($dir, $structure);
			$contacts = $this->findContacts($mail, $account);
			
			$totalContacts = count($contacts);
			
			$contactsM = 0;
			
			foreach ($files->matches as $file) {
				$pdfmail = Pdfmail::findFirst(array(
					'conditions' => 'idMail = ?1 AND name = ?2',
					'bind' => array(1 => $mail->idMail,
									2 => "{$file[0][0]}")
				));

				if ($pdfmail) {
					$name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file[0][0]);
					$clave = array_search($name, $contacts);
					if ($clave != false) {
						$pdfmail->idContact = $clave;
						if (!$pdfmail->save()) {
							foreach ($pdfmail->getMessages() as $msg) {
								$this->logger->log("Error while saving pdfmail: {$msg->getMessage()}");
							}
						}
						$contactsM++;
					}
				}
			}
			
			$result = new stdClass();
			$result->totalfiles = $totalFiles;
			$result->totalfilematch = $files->total;
			$result->totalcontacts = $totalContacts;
			$result->totalcontactsmatch = $contactsM;
			
			return $this->setJsonResponse(array("result" => $result), 200);
		}
		
		$this->view->setVar('mail', $mail);
	}

	private function getTotalFilesInFolder($dir)
	{
		//Obtenemos las cantidad de archivos pdf en el directorio
		return count(glob($dir . "{*.pdf}",GLOB_BRACE));
	}
	
	private function getFilesThatMatch($dir, $structure)
	{
		/* obtenemos los nombres de los archivos PDF */
		$files = glob($dir . "{*.pdf}",GLOB_BRACE);
		$matches = array();
		
		// Recorremos los nombres encontrados y buscamos el texto de la cedula
		$contador = 0;
		foreach ($files as $file) { 
			$filep = explode('/', $file) ;
			$f = $filep[count($filep)-1];
			
			preg_match_all($this->pdf_valid_names[$structure], $f, $result);
			if (!empty($result[1]) || !empty($result[0])) {
				$matches[] =  $result;
				$contador++;
			}	
		}
		
		$result = new stdClass();
		$result->total = $contador;
		$result->matches = $matches;
		
		return $result;
	}
	
	
	public function getCustomfield($idDbase)
	{
		$customfield = Customfield::findFirst(array(
			'conditions' => "idDbase = ?1 AND (name = 'cc' OR name = 'CC')",
			'bind' => array(1 => $idDbase)
		));
		
		if (!$customfield) {
			return false;
		}
		
		return $customfield;
	}
	
	public function findContacts($mail, $account)
	{
		$interpreter = new \EmailMarketing\General\Misc\InterpreterTarget();
		$interpreter->setMail($mail);
		$interpreter->setAccount($account);
		$interpreter->searchContacts();
		$sql = $interpreter->getSQLForSearchContacts();
		
		$executer = new \EmailMarketing\General\Misc\SQLExecuter();
		$executer->instanceDbAbstractLayer();
		$executer->setSQL($sql);
		$executer->queryAbstractLayer();
		$result = $executer->getResult();
		
		$contacts = array();
		$c = array();
		$dbase = 0;
		
		if (count($result) > 0) {
			$dbase = $result[0]['idDbase'];
			
			foreach ($result as $r) {
				$c[] = $r['idContact'];
			}
			
			$customf = $this->getCustomfield($dbase);
			$ids = implode(',', $c);
			
			$sql = "SELECT c.idContact AS contact, fi.textValue AS cc1, fi.numberValue AS cc2
						FROM contact AS c
						JOIN fieldinstance AS fi ON (fi.idContact = c.idContact AND idCustomField = {$customf->idCustomField})
					WHERE c.idContact IN ({$ids})";
			
			$executer = new \EmailMarketing\General\Misc\SQLExecuter();
			$executer->instanceDbAbstractLayer();
			$executer->setSQL($sql);
			$executer->queryAbstractLayer();
			$cs = $executer->getResult();
			
			if (count($cs) > 0) {
				foreach ($cs as $c) {
					$contacts[$c['contact']] = (empty($c['cc1']) ? $c['cc2'] : $c['cc1']);
				}
			}
		}
		
		return $contacts;
	}
	
	public function deleteAction()
	{
		
	}
	
	public function terminateAction($idMail) 
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => "idAccount = ?1 AND idMail = ?2 AND status = 'Draft' AND pdf = 1",
			'bind' => array(1 => $account->idAccount,
							2 => $idMail)
		));
		
		if (!$mail) {
			return $this->setJsonResponse(array('Error' => 'El correo que intenta programar no existe, por favor valide la información'), 404);
		}
		
		if ($this->request->isPost()) {
			try {
				$schedule = $this->request->getPost("schedule");
				if(!empty($schedule)) {
					if ($schedule == 'now') {
						$mail->scheduleDate = time();
					}
					else if ($schedule !== '' || !empty($schedule)) {
						list($day, $month, $year, $hour, $minute) = preg_split('/[\s\/|-|:]+/', $schedule);
						$mail->scheduleDate = mktime($hour, $minute, 0, $month, $day, $year);
					}
				}
				
				if (!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->logger->log("Error while saving pdfstructure in mail... {$msg->getMessage()}");
						throw new Exception("Error while saving mail... {$msg->getMessage()}");
					}
				}
				
				$mailSchedule = new MailScheduleObj($mail);
				$scheduled = $mailSchedule->scheduleTask();

				if (!$scheduled) {
					$this->logger->log("Error while saving mail {$this->mail->idMail} scheduleDate in Mailschedule table account {$this->mail->idAccount}");
					throw new Exception('Error while saving mail scheduleDate in Mailschedule table');
				}
				
				//retornamos un array con la información de los archivos
				return $this->setJsonResponse(array('success' => "Se ha programado el correo exitosamente"), 200);
			}
			catch (Exception $ex) {
				$this->logger->log("Exception: {$ex}");
				return $this->setJsonResponse(array('Error' => 'Ocurrió un error, por favor contacte al administrador'), 500);
			}
		}
		
		$this->view->setVar('mail', $mail);
	}
	
	public function sendtestAction($idMail)
	{
		$account = $this->user->account;
		$mail = Mail::findFirst(array(
			'conditions' => "idAccount = ?1 AND idMail = ?2 AND status = 'Draft' AND pdf = 1",
			'bind' => array(1 => $account->idAccount,
							2 => $idMail)
		));
		
		if (!$mail) {
			$this->response->redirect("error");
		}
		
		if ($this->request->isPost()) {
			$mailContent = Mailcontent::findFirst(array(
				'conditions' => 'idMail = ?1',
				'bind' => array(1 => $idMail)
			));
			
			$id = $this->request->getPost("id");
			$message = $this->request->getPost("message");
			
			if (trim($id) === '') {
				$this->flashSession->error("Ha enviado campos vacíos por favor valide la información");
				return $this->response->redirect("pdfmail/terminate/{$idMail}");
			}
			
			$transport = Swift_SendmailTransport::newInstance();
			$swift = Swift_Mailer::newInstance($transport);
			
			$domain = Urldomain::findFirstByIdUrlDomain($account->idUrlDomain);
			
			$testMail = new TestMail();
			$testMail->setAccount($account);
			$testMail->setDomain($domain);
			$testMail->setUrlManager($this->urlManager);
			$testMail->setMail($mail);
			$testMail->setMailContent($mailContent);
			$testMail->setPersonalMessage($message);

			try {
				$testMail->load();
				
				$sql = "SELECT c.idContact, c.name, c.lastName, e.email
						FROM contact AS c
							JOIN customfield AS cf ON (cf.name = 'cc' OR cf.name = 'CC')
							JOIN fieldinstance AS fi ON (fi.idCustomfield = cf.idCustomfield AND fi.idContact = c.idContact)
							JOIN email AS e ON (e.idEmail = c.idEmail)
						WHERE  fi.textValue = :id1 OR fi.numberValue = :id2";
				
				$result = $this->db->query($sql, array("id1" => "{$id}", 
													   "id2" => $id));
				$contact = $result->fetchAll();
				
				$this->logger->log("Contact: " . print_r($contact, true));
				
				if (count($contact) > 0) {
					$mssg = new Swift_Message($mail->subject);
					$mssg->setFrom(array($mail->fromEmail => $mail->fromName));
					$mssg->setTo(array($contact[0]['email'] => "{$contact[0]['name']} {$contact[0]['lastName']}"));
					$mssg->setBody($testMail->getBody(), 'text/html');
					$mssg->addPart($testMail->getPlainText(), 'text/plain');

					if ($mail->replyTo != null) {
						$message->setReplyTo($mail->replyTo);
					}

					if ($mail->pdf == 1) {
						$pdfmail = Pdfmail::findFirst(array(
							'conditions' => 'idMail = ?1 AND idContact = ?2',
							'bind' => array(1 => $mail->idMail,
											2 => $contact[0]['idContact'])
						));

						if ($pdfmail) {
							$name = "{$id}.pdf";
							$dir = "{$this->asset->dir}{$account->idAccount}/pdf/{$mail->idMail}/{$pdfmail->name}";
							$mssg->attach(Swift_Attachment::fromPath($dir)->setFilename($name));
						}
					}

					$sendMail = $swift->send($mssg, $failures);

					if (!$sendMail){
						$this->logger->log("Error while sending test mail: " . print_r($failures));
						$this->flashSession->error("Ha ocurrido un error mientras se intentaba enviar el correo de prueba, contacte al administrador");
					}

					$this->flashSession->success("Se ha enviado el mensaje de prueba exitosamente");
				}
				else {
					$this->flashSession->warning("No se ha encontrado un contacto relacionado con el id enviado, por favor valide la información");
				}
				
				return $this->response->redirect("pdfmail/terminate/{$idMail}");
			}
			catch (Exception $e) {
				$this->logger->log("Exception, Error while sending test, {$e}");
				$this->flashSession->error("Ha ocurrido un error mientras se intentaba enviar el correo de prueba, contacte al administrador");
				return $this->response->redirect("pdfmail/terminate/{$idMail}");
			}
			catch (\InvalidArgumentException $e) {
				$this->logger->log("Exception, Error while sending test, {$e}");
				$this->flashSession->error("Ha ocurrido un error mientras se intentaba enviar el correo de prueba, contacte al administrador");
				return $this->response->redirect("pdfmail/terminate/{$idMail}");
			}
		}
		
		$this->view->setVar('mail', $mail);
	}
}