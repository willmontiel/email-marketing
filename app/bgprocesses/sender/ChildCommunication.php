<?php
require_once "../../../public/swiftmailer-5.0.3/lib/swift_required.php";
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
		$log = Phalcon\DI::getDefault()->get('logger');
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		$mailContent = Mailcontent::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $mail->idMail)
		));
		
		echo 'Empecé el proceso con MTA!' .PHP_EOL;
		if ($mail && $mailContent) {
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
					'conditions' => 'idMail = ?1',
					'bind' => array(1 => $idMail)
				));
				
				$account = Account::findFirstByIdAccount($mail->idAccount);
				$this->setAccount($account);
				$domain = Urldomain::findFirstByIdUrlDomain($account->idUrlDomain);

				$dbases = Dbase::findByIdAccount($this->account->idAccount);
				$id = array();
				foreach ($dbases as $dbase) {
					$id[] = $dbase->idDbase;
				}

				$idDbases = implode(', ', $id);
				
				if ($oldstatus == 'Scheduled') {
					$log->log("Identificando destinatarios");
					$identifyTarget = new IdentifyTarget();
					$identifyTarget->identifyTarget($mail);
				}
				
				if (trim($mailContent->content) === '') {
					throw new \InvalidArgumentException("Error mail's content is empty");
				}
				else if ($mail->type == 'Editor') {
					$htmlObj = new HtmlObj();
	//				$this->log->log("Content editor: " . print_r(json_decode($mailContent->content), true));
					$htmlObj->assignContent(json_decode($mailContent->content));
					$html = utf8_decode($htmlObj->replacespecialchars($htmlObj->render()));
	//				$this->log->log('Json: ' . $content);
				}
				else {
	//				$this->log->log("No Hay editor");
					$html =  html_entity_decode($mailContent->content);
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
				
				$_ENV['TMPDIR']='/memorydisk/tmp';
				$_ENV['TMP']=$_ENV['TMPDIR'];
				
				// Crear transport y mailer
				$transport = Swift_SmtpTransport::newInstance($this->mta->domain, $this->mta->port);
				$swift = Swift_Mailer::newInstance($transport);
				
				$i = 0;
				$sentContacts = array();
				Phalcon\DI::getDefault()->get('timerObject')->startTimer('Sending', 'Sending message with MTA');
				foreach ($contactIterator as $contact) {
					
					$msg = $this->childprocess->Messages();
					
					if ($fields) {
						$c = $mailField->processCustomFields($contact);
						$subject = $c['subject'];
						$html = $c['html'];
						$text = $c['text'];
					}
					else {
						$subject = $mail->subject;
						$html = $content->html;
						$text = $content->text;
					}
					
					$trackingObj = new TrackingUrlObject();
					$htmlWithTracking = $trackingObj->getTrackingUrl($html, $idMail, $contact['contact']['idContact'], $links);
					
//					$log->log("HTML: " . $htmlWithTracking);
					
					$from = array($mail->fromEmail => $mail->fromName);
					$to = array($contact['email']['email'] => $contact['contact']['name'] . ' ' . $contact['contact']['lastName']);
					
					$message = new Swift_Message($subject);
					
					/*Cabeceras de configuración para evitar que Green Arrow agregue enlaces de tracking*/
					$headers = $message->getHeaders();
					
					if ($this->account->virtualMta == null || trim($this->account->virtualMta) === '') {
						$mta = 'CUST_SIGMA';
					}
					else {
						$mta = $this->account->virtualMta;
					}
					
					$rp = Returnpath::findFirstByIdReturnPath($this->account->idReturnPath);
					
					$listID = 't0em' . $this->account->idAccount;
					$sendID = '0em' . $mail->idMail;
					$trackingID = 'em' . $mail->idMail . 'x' . $contact['contact']['idContact'];
					
					$verpFormat = str_replace('@', '=', $contact['email']['email']);
					$mailClass = $this->mta->mailClass . $sendID;
					$returnPathData = $listID . '-' . $mailClass . '-' . $verpFormat;
					$returnPath = str_replace('(verp)', $returnPathData, $rp->path);
					
//					$headers->addTextHeader('X-GreenArrow-MailClass', 'SIGMA_NEWEMKTG_DEVEL');
					$headers->addTextHeader('X-GreenArrow-MtaID', $mta);
					$headers->addTextHeader('X-GreenArrow-InstanceID', $sendID);
//					$headers->addTextHeader('X-GreenArrow-SendID', $sendID);
					$headers->addTextHeader('X-GreenArrow-Click-Tracking-ID', $trackingID);
					$headers->addTextHeader('X-GreenArrow-ListID', $listID);
					$headers->addTextHeader('List-Unsubscribe', $trackingObj->getUnsubscribeLink());
					
					$message->setFrom($from);
					$message->setBody($htmlWithTracking, 'text/html');
					$message->setTo($to);
					$message->setReturnPath($returnPath);
					$message->addPart($text, 'text/plain');
					
//					$recipients = true;
					$recipients = $swift->send($message, $failures);
					$this->lastsendheaders = $message->getHeaders()->toString();
					$log->log("Headers: " . print_r($this->lastsendheaders, true));
					if ($recipients){
						echo "Message " . $i . " successfully sent! \n";
//						$log->log("HTML: " . $html);
//						$log->log("Headers: " . $this->lastsendheaders);
						$log->log("Message successfully sent! with idContact: " . $contact['contact']['idContact']);
						$sentContacts[] = $contact['contact']['idContact'];
						$lastContact = end(end($contactIterator));
						if (count($sentContacts) == self::CONTACTS_PER_UPDATE || $contact['contact']['idContact'] ==  $lastContact['contact']['idContact'] || $msg == "Stop") {
							$idsContact = implode(', ', $sentContacts);
							$phql = "UPDATE Mxc SET status = 'sent' WHERE idMail = {$mail->idMail} AND idContact IN ({$idsContact})";
							
							$mm = Phalcon\DI::getDefault()->get('modelsManager');
							$mm->executeQuery($phql);
					
							if ($mm) {
//								echo "state's the first 20 changed successfully! \n";
								unset($sentContacts);
								$sentContacts = array();
							}
							else {
								$log->log("Error guardando");
							}
						}
						$i++;
					} 
					else {
						echo "There was an error in message {$i}: \n";
						$log->log("Error while sending mail: " . print_r($failures));
						print_r($failures);
					}
//					$log->log("HTML: " . $html);
//					echo 'Hrml: ' . $html;
					switch ($msg) {
						case 'Cancel':
							$log->log('Estado: Me Cancelaron');
							
							$phql = "UPDATE Mxc SET status = 'canceled' WHERE idMail = " . $mail->idMail;
							$mm = Phalcon\DI::getDefault()->get('modelsManager');
							$mm->executeQuery($phql);
							if (!$mm) {
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
				Phalcon\DI::getDefault()->get('timerObject')->endTimer('all messages sent!');
				
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
				// Grabar profiling de la base de datos
				print_dbase_profile();
				
				if($mail->socialnetworks != null) {
					$socials = new SocialNetworkConnection($log);
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
		}
		else {
			$log->log('The Mail do not exist, or The html content is incomplete or invalid!');
		}
		
	}
	
	protected function checkMailStatus($mail)
	{
		if($mail->status != 'Paused' && $mail->status != 'Scheduled') {
			throw new MailStatusException('El correo no tiene estados Pausado o Programado. Estados no permitidos, en el Mail con ID '. $mail->idMail . ' Con estado ' . $mail->status);
		}
	}
}
