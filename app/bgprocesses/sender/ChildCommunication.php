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
				
				$log->log("customfield {$customFields}");
				$contactIterator = new ContactIterator($mail, $customFields);
				$disruptedProcess = FALSE;
				foreach ($contactIterator as $contact) {
					if ($fields) {
						$c = $mailField->processCustomFields($contact);
						$log->log("Html: " . $c['html']);
//						$log->log("Text: " . $c['text']);
//						$log->log("Subject: " . $c['subject']);
					}
					else {
						$log->log("Html: " . $content->html);
					}
//					$log->log("Contact: " . print_r($contact, true));
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
				}
				
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