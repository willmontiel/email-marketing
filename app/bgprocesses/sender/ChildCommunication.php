<?php
require_once "swift_required.php";
class ChildCommunication extends BaseWrapper
{
	protected $socket;
	
	public function __construct() 
	{
		$di =  \Phalcon\DI\FactoryDefault::getDefault();
	
		$this->mta = $di['mtadata'];
	}
	
	public function setSocket($socket)
	{
		$this->socket = $socket;
	}
	
	public function startProcess($idMail)
	{
		$log = Phalcon\DI::getDefault()->get('logger');
		$mail = Mail::findFirst(array(
			'conditions' => 'idMail = ?1',
			'bind' => array(1 => $idMail)
		));
		
		echo 'Empecé el proceso con MTA!';
		if ($mail) {
			$mail->status = 'Sending';
			$mail->startedon = time();
			$mail->save();
			
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
				
				$i = 0;
				Phalcon\DI::getDefault()->get('timerObject')->startTimer('Sending', 'Sending message with MTA');
				foreach ($contactIterator as $contact) {
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
						echo "Message {$i} successfully sent! \n";
					} 
					else {
						echo "There was an error in message {}: \n";
						print_r($failures);
					}
					$log->log("HTML: " . $html);
					$msg = $this->socket->Messages();
					switch ($msg) {
						case 'Cancel':
							$mail->status = 'Cancelled';
							$disruptedProcess = TRUE;
							break 2;
						case 'Stop':
							$mail->status = 'Paused';
							$disruptedProcess = TRUE;
							break 2;
					}
					$i++;
				}
				Phalcon\DI::getDefault()->get('timerObject')->endTimer('all messages sent!');
				if(!$disruptedProcess) {
					$mail->status = 'Sent';
					$mail->finishedon = time();
				}
				$mail->save();
			}
			catch (InvalidArgumentException $e) {

			}
		}
		
	}
}