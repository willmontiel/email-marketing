<?php
require_once "../app/library/swiftmailer/lib/swift_required.php";

class PdfmailController extends ControllerBase
{
	public $pdf_valid_names = array(
		1 => '/^NI_([0-9]+)_CC_([0-9]+)_[0-9].*\.pdf$/',//NI_12345678_CC_12345678_1.pdf
		2 => '/^NI_([0-9]+)_CC_([0-9]+)_[0-9]_([0-9]+).*\.pdf$/',//NI_12345678_CC_12345678_1_12345678.pdf
		3 => '/^NI_([0-9]+)_CC_([0-9]+)_[0-9]_[0-9].*\.pdf$/',//NI_12345678_CC_12345678_1_1.pdf
		4 => '/^NI_([0-9]+)_CC_([0-9]+)*\.pdf$/', //NI_12345678_CC_12345678.pdf
		5 => '/^NI_([0-9]+)_.._([0-9]+)*\.pdf$/', //NI_12345678_.._12345678.pdf
		6 => '/^([0-9]+)*_[A-Z]*_[A-Z].*\.pdf/', //12345678_PEPITO_PEREZ.pdf
		7 => '/^([0-9]+)*_[a-z]*_[a-z].*\.pdf/', //12345678_pepito_perez.pdf
		8 => '/^([0-9]+)*_[A-Z].*\.pdf/', //3132319_PEPITO_PEREZ.pdf o 3132319_PEPITO_.pdf
		9 => '/^NI_[0-9]*_([0-9]+)_CC.*\.pdf$/',//NI_860508392
		10 => '/^([0-9]+).*\.pdf/', //11523458.pdf
		11 => '/^[0-9]*_([0-9]+).*\.pdf/', //20130306152836_1013586831_310495422_86824.pdf
		12 => '/^[A-Z]*_([0-9]+).*\.pdf/', //CC_2986509.pdf ó CE_2986509.pdf o CC_6108950_201306.pdf
		13 => '/^[A-Z]*([0-9]+).*\.pdf/', //CC2986509.pdf
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
			if (empty($_FILES['Filedata']['name'])) {
				return $this->setJsonResponse(array(
												'error' => 'No ha enviado ningún archivo o ha enviado un tipo de archivo no soportado, por favor verifique la información')
												, 400 , 'Archivo vacio o incorrecto');
			}
			else {
				$data = new stdClass();
				$data->name = $_FILES['Filedata']['name'];
				$data->size = $_FILES['Filedata']['size'];
				$data->type = $_FILES['Filedata']['type'];
				$data->tmp_dir = $_FILES['Filedata']['tmp_name'];
				
				$ext = array('pdf','PDF');
				
				try {
					$uploader = new \EmailMarketing\General\Misc\Uploader();
					$uploader->setAccount($account);
					$uploader->setMail($mail);
					$uploader->setData($data);
					$uploader->validateExt($ext);
					$uploader->validateSize(2048);
					
					$this->db->begin();
					
					$pdf = new Pdfmail();
					$pdf->idMail = $mail->idMail;
					$pdf->name = $data->name;
					$pdf->size = $data->size;
					$pdf->type = $data->type;
					$pdf->createdon = time();
					
					if (!$pdf->save()) {
						foreach ($pdf->getMessages() as $msg) {
							throw new Exception("Error while saving pdfmail... {$msg->getMessage()}");
						}
					}
					
					$uploader->uploadFile();
					
					$this->db->commit();
					return $this->setJsonResponse(array("success" => "Se ha cargado el archivo exitosamente"), 200);
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
			//Obtenemos las cantidad de archivos pdf en el directorio
			$num_file = count(glob($dir . "{*.pdf}",GLOB_BRACE));
			/* obtenemos los nombres de los archivos PDF */
			$files = glob($dir . "{*.pdf}",GLOB_BRACE);
			
			$contador = 0;
			$totalm = 0;
			$matches = array();
			// Recorremos los nombres encontrados y buscamos el texto de la cedula
			foreach ($files as $file) { 
				$archivos[] = array_pop(split("/",$file));
				
				$totalm += preg_match_all($this->pdf_valid_names[$structure], $archivos[$contador], $result);
				
				$matches[] =  $result;
				$contador = $contador +1;
			}
			
			$data = array(
				'total' => $num_file, 
				'totalm' => $totalm
			);
			
			$mail->pdfstructure = $structure;
			
			if (!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log("Error while saving pdfstructure in mail... {$msg->getMessage()}");
				}
				return $this->setJsonResponse(array('Error' => 'Ocurrió un error, por favor contacte al administrador'), 500);
			}
			//retornamos un array con la información de los archivos
			return $this->setJsonResponse(array('result' => $data), 200);
		}
		
		$this->view->setVar('mail', $mail);
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
			$this->response->redirect("error");
		}
		
		if ($this->request->isPost()) {
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
				}
				return $this->setJsonResponse(array('Error' => 'Ocurrió un error, por favor contacte al administrador'), 500);
			}
			//retornamos un array con la información de los archivos
			return $this->setJsonResponse(array('success' => "Se ha programado el correo exitosamente"), 200);
			
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
			
		}
		
		$this->view->setVar('mail', $mail);
	}
}