<?php
require_once '../bootstrap/phbootstrap.php';

$obj = new CheckASProcess();
$obj->start_process();

class CheckASProcess
{
	
	const SCHEDULING_INTERVAL_IN_SECONDS = 604800;
	
	function __construct() {
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function start_process()
	{
		try {
			$modelManager = Phalcon\DI::getDefault()->get('modelsManager');

			$before = time() - self::SCHEDULING_INTERVAL_IN_SECONDS;
			$after = time() + self::SCHEDULING_INTERVAL_IN_SECONDS;

			$parameters = array('before' => $before, 'after' => $after);
			$querytxt = "SELECT * FROM Autoresponder WHERE nextExecution BETWEEN :before: AND :after:";

			$autoresponders = $modelManager->executeQuery($querytxt, $parameters);

			$mails = array();

			foreach ($autoresponders as $autoresponder) {
				$account = Account::findFirstByIdAccount($autoresponder->idAccount);
				if($account) {
					$mailconverter = new AutoSendingConverter();
					$mailconverter->setAutoresponder($autoresponder);
					$mailconverter->setAccount($account);
					$mailconverter->convertToMail();
					$mails[] = $mailconverter->getMail();
				}
			}
			
			foreach ($mails as $mail) {
				$this->logger->log('El idMail es ' . $mail->idMail);
				$this->send_autoresponders($mail->idMail);
			}
		}
		catch(Exception $e) {
			$this->logger->log('Error ' . $e->getMessage());
		}
		catch(InvalidArgumentException $e) {
			$this->logger->log('Error ' . $e->getMessage());
		}
	}
	
	public function send_autoresponders($idMail)
	{
		$schedule = Mailschedule::findFirstByIdMail($idMail);
		$mail = Mail::findFirstByIdMail($idMail);
		try {
			if($schedule) {
				$mail->status = 'Scheduled';
				if(!$mail->save()) {
					foreach ($mail->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					$this->traceFail("Error confirming mail, idMail: {$idMail}");
					return $this->response->redirect('mail/preview/' . $idMail);
				}

				$schedule->confirmationStatus = 'Yes';
				if(!$schedule->save()){
					foreach ($schedule->getMessages() as $msg) {
						$this->flashSession->error($msg);
					}
					$this->traceFail("Error confirming mail, idMail: {$idMail}");
					return $this->response->redirect('mail/preview/' . $idMail);
				}
				$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
				$commObj->sendSchedulingToParent($idMail);	

				return $this->response->redirect("mail/index");
			}
		}
		catch (Exception $e) {
			$this->logger->log("Exception: Error confiming mail, {$e}");
			return $this->response->redirect('mail/preview/' . $idMail);
		}
	}
}