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
				try {
					$this->logger->log('Enviando idMail ' . $mail->idMail);
					$this->send_autoresponders($mail);
				}
				catch (Exception $e) {
					$this->logger->log("Exception: Error sending auto responder, {$e}");
				}
			}
		}
		catch(Exception $e) {
			$this->logger->log('Error ' . $e->getMessage());
		}
		catch(InvalidArgumentException $e) {
			$this->logger->log('Error ' . $e->getMessage());
		}
	}
	
	public function send_autoresponders($mail)
	{
		$schedule = Mailschedule::findFirstByIdMail($mail->idMail);
		$this->logger->log('Mail ' . $mail->idMail);
		$this->logger->log('Schedule ' . $schedule->idMailSchedule);		
		if($schedule) {
			$mail->status = 'Scheduled';
			$this->logger->log('1');
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				throw new Exception('Error saving mail in auto responder');
			}

			$schedule->confirmationStatus = 'Yes';
			$this->logger->log('Mail status ' . $mail->status);
			$this->logger->log('2');
			if(!$schedule->save()){
				foreach ($schedule->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				throw new Exception('Error saving scheduling in auto responder');
			}
			$this->logger->log('Schedule status ' . $schedule->confirmationStatus );
			$this->logger->log('3');
			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$this->logger->log('4');
			$commObj->sendSchedulingToParent($idMail);	
			$this->logger->log('5');
		}
	}
}