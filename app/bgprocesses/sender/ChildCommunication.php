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
				$idsCustomField = $mailField->getCustomFields();
				$log->log("customfield {$idsCustomField}");
				$contactIterator = new ContactIterator($mail, $idsCustomField);
//				$i = 0;
				foreach ($contactIterator as $contact) {
					$c = $mailField->processCustomFields($contact);
					$log->log("Html: " . $c['html']);
//					$log->log("Text: " . $c['text']);
//					$log->log("Subject: " . $c['subject']);
//					$log->log("Contact: " . print_r($contact, true));
//					$i++;
					$command = $this->socket->Messages();
					if($command == 'Cancel') {
						 break;
					}
				}
//				$log->log("Finalice! {$i} iteraciones");
			}
			catch (InvalidArgumentException $e) {

			}
		}
		
	}
}