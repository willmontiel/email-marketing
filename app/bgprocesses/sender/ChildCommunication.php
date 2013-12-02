<?php
class ChildCommunication extends BaseWrapper
{
	protected $socket;
	
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

		if ($mail) {
			$mail->status = 'Sending';
			$mail->startedon = time();
			$mail->save();
			
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
				
				$log->log("customfield {$customFields}");
				$contactIterator = new ContactIterator($mail, $customFields);
				foreach ($contactIterator as $contact) {
					if ($fields) {
						$c = $mailField->processCustomFields($contact);
						$log->log("Html: " . $c['html']);
	//					$log->log("Text: " . $c['text']);
	//					$log->log("Subject: " . $c['subject']);
					}
					$log->log("Html: " . $content->html);
//					$log->log("Contact: " . print_r($contact, true));
					$command = $this->socket->Messages();
					if($command == 'Cancel') {
						 break;
					}
				}
				
				$mail->status = 'Sent';
				$mail->finishedon = time();
				$mail->save();
			}
			catch (InvalidArgumentException $e) {

			}
		}
		
	}
}