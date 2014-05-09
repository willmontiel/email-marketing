<?php
require_once "../../library/swiftmailer/lib/swift_required.php";
class ChildCommunication extends BaseWrapper
{
	protected $childprocess;
	const CONTACTS_PER_UPDATE = 25;
	
	public function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	
		$this->mta = $di['mtadata'];
		$this->urlManager = $di['urlManager'];
	}
	
	public function setSocket($childprocess)
	{
		$this->childprocess = $childprocess;
	}

	public function startProcess($idMail)
	{
		$timer = Phalcon\DI::getDefault()->get('timerObject');
		$timer->reset();
		$timer->startTimer('send-process', 'Sending message with MTA');
		
		$log = Phalcon\DI::getDefault()->get('logger');
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');

		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1 AND deleted = 0',
			'bind' => array(1 => $idMail)
		));
		
		$mailContent = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));

		
		/*
		 * ================================================================
		 * NOTA
		 * Moví la validación del mail a este punto para cerrarlo antes
		 * ================================================================
		 */
		if (!$mail || !$mailContent) {
			$log->log('The Mail do not exist, or The html content is incomplete or invalid!');
			
			/*
			 * ================================================================
			 * ERROR
			 * REVISAR: Debería hacerse algo con el estado del mail cuando tiene
			 * problemas... el usuario no sabe que sucedió y sigue en estado
			 * SCHEDULED!
			 * ================================================================
			 */
			$timer->startTimer('send-process');
			return;
		}
		
		
		echo 'Empecé el proceso con MTA!' .PHP_EOL;
		try {
			$this->checkMailStatus($mail);
			$oldstatus = $mail->status;
			$mail->status = 'Sending';
			$mail->startedon = time();
			if(!$mail->save()) {
				$log->log('No se pudo actualizar el estado del MAIL');
				throw new MailStatusException('No se pudo actualizar el estado del MAIL a SENDING');
			}		

			$mail = Mail::findFirst(array(
				'conditions' => 'idMail = ?1 AND deleted = 0',
				'bind' => array(1 => $idMail)
			));

			$timer->startTimer('send-preparation', 'Preparing everything to send email');

			$account = Account::findFirstByIdAccount($mail->idAccount);
			$this->setAccount($account);
			$domain = Urldomain::findFirstByIdUrlDomain($account->idUrlDomain);

			$dbases = Dbase::findByIdAccount($this->account->idAccount);
			$id = array();
			foreach ($dbases as $dbase) {
				$id[] = $dbase->idDbase;
			}
			// Lista de todas las bases de datos de la cuenta
			// Se usa para identificar los campos personalizados
			$idDbases = implode(', ', $id);

			/*
			 * =========================================================
			 * NOTA
			 * Cambié el siguiente IF de ubicacion...
			 * Validar contenido (que no esta vacio) antes de realizar
			 * la identificación de destinatarios (y la insercion
			 * en mxc!!!
			 * =========================================================
			 */
			if (trim($mailContent->content) === '') {
				throw new \InvalidArgumentException("Error mail's content is empty");
			}

			if ($oldstatus == 'Scheduled') {
				$log->log("Identificando destinatarios");
				$identifyTarget = new IdentifyTarget();
				$identifyTarget->identifyTarget($mail);
			}

			if ($mail->type == 'Editor') {
				$htmlObj = new HtmlObj();
//				$this->log->log("Content editor: " . print_r(json_decode($mailContent->content), true));
				$htmlObj->assignContent(json_decode($mailContent->content));
				$html = utf8_decode($htmlObj->replacespecialchars($htmlObj->render()));
//				$this->log->log('Json: ' . $content);
			}
			else {
//				$this->log->log("No Hay editor");
				$html =  utf8_decode(html_entity_decode($mailContent->content));
			}

//				$prepareMail = new PrepareMailContent($this->account);
//				$content = $prepareMail->getContentMail($html);
			$urlManager = $this->urlManager;
			$imageService = new ImageService($account, $domain, $urlManager);
			$linkService = new LinkService($account, $mail);
			$prepareMail = new PrepareMailContent($linkService, $imageService);
			list($content, $links) = $prepareMail->processContent($html);

			$mailField = new MailField($content, $mailContent->plainText, $mail->subject, $idDbases);
			$cf = $mailField->getCustomFields();

			switch ($cf) {
				case 'No Fields':
					$customFields = false;
					$fields = false;
					break;
				case 'No Custom':
					$fields = true;
					$customFields = false;
					break;
				default:
					$fields = true;
					$customFields = $cf;
					break;
			}

			$contactIterator = new ContactIterator($mail, $customFields);
			$disruptedProcess = FALSE;

			/*
			 * ================================================================
			 * NOTA
			 * TODO: Cual es el propósito de estas dos líneas???
			 * Por qué son valores codificados???
			 * ================================================================
			 */
			$_ENV['TMPDIR']='/memorydisk/tmp';
			$_ENV['TMP']=$_ENV['TMPDIR'];

			// Crear transport y mailer
			$transport = Swift_SmtpTransport::newInstance($this->mta->address, $this->mta->port);
			$swift = Swift_Mailer::newInstance($transport);

			$i = 0;
			$sentContacts = array();
			Phalcon\DI::getDefault()->get('timerObject')->startTimer('Sending', 'Sending message with MTA');
			
			$from = array($mail->fromEmail => $mail->fromName);
			
			// Crear objeto que se encarga de insertar links para cada usuario
			$trackingObj = new TrackingUrlObject();
			
			// Consultar el mail class para inyectarselo en las cabeceras del correo con switmailer
			$mailclass = Mailclass::findFirstByIdMailClass($this->account->idMailClass);
			
			// Crear variables listID y sendID para inyectarlas a las cabeceras con swiftmailer
			$listID = 't0em' . $this->account->idAccount;
			$sendID = '0em' . $mail->idMail;
			
			// MTA a utilizar
			$mta = ($this->account->virtualMta == null || trim($this->account->virtualMta) === '')?'CUST_SIGMA':$this->account->virtualMta;
			
			// Prefijo de tracking ID
			$trIDprefix = 'em' . $mail->idMail . 'x';
	
			$timer->endTimer('send-preparation');

			$timer->startTimer('send-loop', 'In the loop');

			foreach ($contactIterator as $contact) {

				/*
				 * ================================================================
				 * NOTA
				 * Esta validacion de mensajes del proceso padre debería hacerse
				 * después de enviar algunos correos (ej: 10 o 20) para 
				 * evitar sobre-procesamiento
				 * ================================================================
				 */
				$timer->startTimer('yield', 'Yielding...');

				// Recibir y procesar mensajes pendientes del proceso padre (YIELD)
				$msg = $this->childprocess->Messages();
				$timer->endTimer('yield');

				// Reemplazar valores de los campos personalizados en el contenido
				// del correo

				$timer->startTimer('custom-fields', 'Processing Custom Fields...');
				
				if ($fields) {
					$c = $mailField->processCustomFields($contact);
					$subject = $c['subject'];
					$html = $c['html'];
					$text = $c['text'];
				}
				else {
					$subject = $mail->subject;
					$html = $content;
					$text = $mailContent->plainText;
				}
				$timer->endTimer('custom-fields');
				
				$timer->startTimer('tracking-code', 'Creating tracking code...');
				$htmlWithTracking = $trackingObj->getTrackingUrl($html, $idMail, $contact['contact']['idContact'], $links);
				$timer->endTimer('tracking-code');

				// El destinatario (cuando el nombre y apellido estan vacios, se asigna el correo)
				$toNameT = trim($contact['contact']['name'] . ' ' . $contact['contact']['lastName']);
				$toName = (!$toNameT || $toNameT == '')?$contact['email']['email']:$toNameT;
				$to = array($contact['email']['email'] => $toName);

				$timer->startTimer('prepare-msg', 'Preparing message (swift)...');
				$message = new Swift_Message($subject);

				/* Asignacion de headers del mensaje */
				$headers = $message->getHeaders();

				/*
				 * ================================================================
				 * ERROR
				 * ADVERTENCIA: Esta consulta de MAILCLASS se esta realizando
				 * por cada uno de los contactos a los que se envía.
				 * Este valor es global al correo, por lo que sería mejor no hacerlo
				 * por cada contacto!
				 * TODO: Mover fuera del loop
				 * ================================================================
				 */
				$trackingID = $trIDprefix . $contact['contact']['idContact'] . 'x' . $contact['email']['idEmail'];

				$headers->addTextHeader('X-GreenArrow-MailClass', $mailclass->name);
				$headers->addTextHeader('X-GreenArrow-MtaID', $mta);
				$headers->addTextHeader('X-GreenArrow-InstanceID', $sendID);
				$headers->addTextHeader('X-GreenArrow-Click-Tracking-ID', $trackingID);
				$headers->addTextHeader('X-GreenArrow-ListID', $listID);
				$headers->addTextHeader('List-Unsubscribe', $trackingObj->getUnsubscribeLink());

				$message->setFrom($from);
				$message->setBody($htmlWithTracking, 'text/html');
				$message->setTo($to);
				if ($mail->replyTo != null) {
					$message->setReplyTo($mail->replyTo);
				}
				$message->addPart($text, 'text/plain');
				$timer->endTimer('prepare-msg');

				$timer->startTimer('send-msg', 'Sending message (swift)...');
				$recipients = $swift->send($message, $failures);
				$timer->endTimer('send-msg');
				
				if ($recipients) {
//					echo "Message " . $i . " successfully sent! \n";
//						$log->log("HTML: " . $html);
//						$log->log("Headers: " . $this->lastsendheaders);
//					$log->log("Message successfully sent! with idContact: " . $contact['contact']['idContact']);
					$sentContacts[] = $contact['contact']['idContact'];
					/*
					 * ================================================================
					 * NOTA
					 * count($array) es una operacion O(1) o O(n) ???
					 * Si es O(1) no hay problema
					 * Si es O(n) entonces es preferible usar una variable separada
					 * ADVERTENCIA: preferible utilizar 
					 *	if (!($n % self::CONTACTS_PER_UPDATE) ... )
					 * ================================================================
					 */
					if (count($sentContacts) == self::CONTACTS_PER_UPDATE || $msg == "Stop") {
						$timer->startTimer('upd-msg-status', 'Updating message status...');
						$idsContact = implode(', ', $sentContacts);
						$phql = "UPDATE Mxc SET status = 'sent' WHERE idMail = {$mail->idMail} AND idContact IN ({$idsContact})";

						if ($modelsManager->executeQuery($phql)) {
							unset($sentContacts);
							$sentContacts = array();
						}
						else {
							$log->log("Error actualizando el estado de envio de los mensajes!!!");
						}
						$timer->endTimer('upd-msg-status');
					}
					$i++;
				} 
				else {
					echo "There was an error in message {$i}: \n";
					$log->log("Error while sending mail: " . print_r($failures, true));
					print_r($failures);
				}
//					$log->log("HTML: " . $html);
//					echo 'Hrml: ' . $html;
				switch ($msg) {
					case 'Cancel':
						$log->log('Estado: Me Cancelaron');

						$phql = "UPDATE Mxc SET status = 'canceled' WHERE idMail = " . $mail->idMail;
						if (!$modelsManager->executeQuery($phql)) {
							$log->log("Error updating MxC");
						}

						$mail->status = 'Cancelled';
						$mail->finishedon = time();
						$disruptedProcess = TRUE;
						break 2;
					case 'Stop':
						$log->log("Estado: Me Pausaron");
						$mail->status = 'Paused';
						$disruptedProcess = TRUE;
						break 2;
					case 'Checking-Work':
						$log->log('Estado: Verificando');
						$this->childprocess->responseToParent('Work-Checked' , $i);
						break;
				}
			}

			$timer->startTimer('send-postprocessing', 'Right after the loop');
			// || $contact['contact']['idContact'] ==  $lastContact['contact']['idContact']	
			// Grabar ultimos contactos enviados
			if (count($sentContacts) > 0) {
				$idsContact = implode(', ', $sentContacts);
				$phql = "UPDATE Mxc SET status = 'sent' WHERE idMail = {$mail->idMail} AND idContact IN ({$idsContact})";

				if ($modelsManager->executeQuery($phql)) {
					unset($sentContacts);
					$sentContacts = array();
				}
				else {
					$log->log("Error guardando");
				}
			}			
			$timer->endTimer('send-loop');

			if(!$disruptedProcess) {
				$log->log('Estado: Me enviaron');
				$mail->totalContacts = $i;
				$mail->status = 'Sent';
				$mail->finishedon = time();
			}
			$log->log('Se actualizara el estado del MAIL como ' . $mail->status);
			if(!$mail->save()) {
				$log->log('No se pudo actualizar el estado del MAIL');
				throw new MailStatusException('No se pudo actualizar el estado del MAIL a Terminado o finalizacion Abrupta');
			}

			if($mail->socialnetworks != null) {
				$socials = new SocialNetworkConnection();
				$socials->setAccount($this->account);
				$socialdesc = Socialmail::findFirstByIdMail($mail->idMail);
				if($socialdesc) {
					$idsocials = json_decode($mail->socialnetworks);
					if(isset($idsocials->facebook)) {
						$socials->setFacebookConnection(Phalcon\DI::getDefault()->get('fbapp')->iduser, Phalcon\DI::getDefault()->get('fbapp')->token);
						$socials->postOnFacebook($idsocials->facebook, $socialdesc, $mail);
					}
					if(isset($idsocials->twitter)) {
						$socials->setTwitterConnection(Phalcon\DI::getDefault()->get('twapp')->iduser, Phalcon\DI::getDefault()->get('twapp')->token);
						$appids = array(
							'id' => Phalcon\DI::getDefault()->get('twapp')->iduser,
							'secret' => Phalcon\DI::getDefault()->get('twapp')->token
						);
						$socials->postOnTwitter($idsocials->twitter, $socialdesc, $mail, $appids);
					}
				}
				else {
					$log->log('No se encontro descripcion de la publicacion en las redes sociales');
				}
			}
			$timer->endTimer('send-postprocessing');
		}
		catch (InvalidArgumentException $e) {
			$log->log('Exception: [' . $e . ']');
			$mail->status = 'Cancelled';
			$mail->finishedon = time();
			if(!$mail->save()) {
				$log->log('No se pudo actualizar el estado del MAIL');
			}
		}
		catch (MailStatusException $e) {
			$log->log('Exception de Estado de Correo: [' . $e . ']');
		}
		catch (Exception $e) {
			$log->log('Exception General: [' . $e . ']');
		}
		
		$timer->endTimer('send-process');
		// Grabar profiling de la base de datos
		print_dbase_profile();

		$log->log($timer);
		
	}
	
	protected function checkMailStatus($mail)
	{
		if($mail->status != 'Paused' && $mail->status != 'Scheduled') {
			throw new MailStatusException('El correo no tiene estados Pausado o Programado. Estados no permitidos, en el Mail con ID '. $mail->idMail . ' Con estado ' . $mail->status);
		}
	}
}
