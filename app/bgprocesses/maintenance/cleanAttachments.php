<?php
require_once '../bootstrap/phbootstrap.php';

$attManager = new AttachmentManager();
$attManager->cleanAttachments();

class AttachmentManager
{
	public function __construct() 
	{
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function cleanAttachments()
	{
		echo 'Searching old mail attachments ' . date('d/m/Y H:i:s') . PHP_EOL;
		$this->logger->log("Searching old mail attachments" . date('d/m/Y H:i:s'));
		
		$mails = Mail::find();
		$one_month_ago = strtotime("-1 month");
		
		try {
			if (count($mails) > 0) {
				$attachobj = new AttachmentObj();

				foreach ($mails as $mail) {
					if ($mail->finishedon < $one_month_ago) {
						$account = Account::findFirstByIdAccount($mail->idAccount);

						$attachments = Attachment::find(array(
							'conditions' => 'idMail = ?1',
							'bind' => array(1 => $mail->idMail)
						));

						if (count($attachments) > 0 ){
							foreach ($attachments as $attachment) {
								$attachobj->setAccount($account);
								$attachobj->setMail($mail);
								$attachobj->setAttachment($attachment);
								$attachobj->deleteAttachment(false);
								
								echo 'Attachment ' . $attachment->idAttachment . ', with Mail ' . $mail->idMail . ' and createdon ' . date('d/m/Y H:i:s', $attachment->createdon) . ' cleaned' . PHP_EOL;
								$this->logger->log("Attachment {$attachment->idAttachment} with Mail {$mail->idMail} and createdon " . date('d/m/Y H:i:s', $attachment->createdon) . " cleaned");
							}
						}
					}
				}
			}
		}
		catch (Exception $e) {
			$this->logger->log("Exception while cleaning attachment: {$e}");
		}
		
		echo 'Cleaning fishined ' . date('d/m/Y H:i:s') .PHP_EOL;
		$this->logger->log("Cleaning fishined " . date('d/m/Y H:i:s'));
	}
}
