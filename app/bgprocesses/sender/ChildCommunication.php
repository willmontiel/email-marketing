<?php
require_once "../../../swiftmailer-5.0.3/lib/swift_required.php";
class ChildCommunication extends BaseWrapper
{
	protected $childprocess;
	const CONTACTS_PER_UPDATE = 25;
	
	public function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	
		$this->mta = $di['mtadata'];
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
		
		echo 'Empecé el proceso con MTA!' .PHP_EOL;
		if ($mail) {
			
			$account = Account::findFirstByIdAccount($mail->idAccount);
			$this->setAccount($account);
			
			$dbases = Dbase::findByIdAccount($this->account->idAccount);
			$id = array();
			foreach ($dbases as $dbase) {
				$id[] = $dbase->idDbase;
			}
			
			$idDbases = implode(', ', $id);
			try {
				$identifyTarget = new IdentifyTarget();
				$identifyTarget->identifyTarget($mail);
			
				$prepareMail = new PrepareContentMail($this->account);
				$content = $prepareMail->getContentMail($mail);
				
				$mailField = new MailField($content->html, $content->text, $mail->subject, $idDbases);
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
				
				// Crear transport y mailer
				$transport = Swift_SmtpTransport::newInstance($this->mta->domain, $this->mta->port);
				$swift = Swift_Mailer::newInstance($transport);
				
//				if($mail->status == 'Scheduled') {
//					$mail->startedon = time();
//				}
				$mail->status = 'Sending';
				if(!$mail->save()) {
					$log->log('No se pudo actualizar el estado del MAIL');
				}				
				
				$i = 1;
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
					
					$from = array($mail->fromEmail => $mail->fromName);
					$to = array($contact['email']['email'] => $contact['contact']['name'] . ' ' . $contact['contact']['lastName']);
					
					$message = new Swift_Message($subject);
					$message->setFrom($from);
					$message->setBody($html, 'text/html');
					$message->setTo($to);
					$message->addPart($text, 'text/plain');

					$recipients = $swift->send($message, $failures);

					if ($recipients){
//						echo "Message {$i} successfully sent! \n";
//						$log->log("HTML: " . $html);
						$log->log("Message successfully sent! with idContact: " . $contact['contact']['idContact']);
						$sentContacts[] = $contact['contact']['idContact'];
						$lastContact = end(end($contactIterator));
						if (count($sentContacts) == self::CONTACTS_PER_UPDATE || $contact['contact']['idContact'] ==  $lastContact['contact']['idContact'] || $msg == "Stop") {
							$idsContact = implode(', ', $sentContacts);
							$phql = "UPDATE Mxc SET status = 'sent' WHERE idMail = " . $mail->idMail . " AND idContact IN (" . $idsContact . ")";
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
					} 
					else {
						echo "There was an error in message {$i}: \n";
						$log->log("Error while sending mail: " . $failures);
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
							$this->childprocess->responseToParent('Work-Checked' , $i);
							break;
					}
					$i++;
				}
				Phalcon\DI::getDefault()->get('timerObject')->endTimer('all messages sent!');
				
				if(!$disruptedProcess) {
					$log->log('Estado: Me enviaron');
					$mail->status = 'Sent';
					$mail->finishedon = time();
				}

				if(!$mail->save()) {
					$log->log('No se pudo actualizar el estado del MAIL');
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
		}
		
	}
}
