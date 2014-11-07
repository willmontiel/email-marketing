<?php
require_once '../bootstrap/phbootstrap.php';

$obj = new CheckASProcess();
$obj->start_process();

class CheckASProcess
{
	
	const SCHEDULING_INTERVAL_IN_SECONDS = 120;
	const NUMBER_OF_TRIES = 35;
	const TIME_TO_SLEEP = 600;
	
	function __construct() {
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}
	
	public function start_process()
	{
		$this->logger->log('Checking auto responders');
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');

		$before = time() - self::SCHEDULING_INTERVAL_IN_SECONDS;
		$after = time() + self::SCHEDULING_INTERVAL_IN_SECONDS;

		$parameters = array('before' => $before, 'after' => $after);
		$querytxt = "SELECT * FROM Autoresponder WHERE nextExecution BETWEEN :before: AND :after:";

		$autoresponders = $modelManager->executeQuery($querytxt, $parameters);

		$mails = array();
		$pending = array();
		$try_number = 0;
		
		for($i = 0; $i <= self::NUMBER_OF_TRIES; $i++) {
			$pending[$i] = array();
		}
		
		foreach ($autoresponders as $autoresponder) {
			try {
				$account = Account::findFirstByIdAccount($autoresponder->idAccount);
				$responder = $this->convert_autoresponder_to_mail($autoresponder, $account);
				if($responder) {
					$mails[]= $responder;
				}
			}
			catch(Exception $e) {
				if($e->getMessage() == 500) {
					$pending[$try_number][]= $autoresponder;
					$this->send_server_error_mail($autoresponder, $account);
				}
			}

			try {	
				$this->set_next_sending($autoresponder);
			}
			catch(Exception $e) {
				$this->logger->log('Error ' . $e->getMessage());
			}
		}

		if(!empty($mails)) {
			foreach ($mails as $mail) {
				try {
					$this->send_autoresponders($mail);
					$this->send_success_mail_to_support($mail);
				}
				catch (Exception $e) {
					$this->logger->log("Exception: Error sending auto responder, {$e}");
				}
			}
		}
		
		$pending_mails = array();			
		while( $try_number < self::NUMBER_OF_TRIES && !empty($pending[$try_number])) {
			sleep(self::TIME_TO_SLEEP);
			foreach ($pending[$try_number] as $autoresponder) {
				try {
					$account = Account::findFirstByIdAccount($autoresponder->idAccount);
					$pending_responder = $this->convert_autoresponder_to_mail($autoresponder, $account);
					if($pending_responder) {
						$pending_mails[]= $pending_responder;
					}
				}
				catch(Exception $e) {
					if($e->getMessage() == 500) {
						$pending[$try_number + 1][]= $autoresponder;
						$this->send_server_error_mail($autoresponder, $account);
					}
				}
				if(!empty($pending_mails)) {
					foreach ($pending_mails as $pmail) {
						try {
							$this->send_autoresponders($pmail);
							$this->send_success_mail_to_support($pmail);
						}
						catch (Exception $e) {
							$this->logger->log("Exception: Error sending auto responder, {$e} on the try number {$try_number}");
						}
					}
				}
			}
			$try_number++;
		}
	}
	
	public function convert_autoresponder_to_mail($autoresponder, Account $account)
	{
		if($account && $autoresponder->active == 1) {
			$mailconverter = new AutoSendingConverter();
			$mailconverter->setAutoresponder($autoresponder);
			$mailconverter->setAccount($account);
			$mailconverter->convertToMail();
			return $mailconverter->getMail();
		}
		return false;
	}
	
	public function set_next_sending($autoresponder)
	{
		$time = json_decode($autoresponder->time);
		$nextmailing = new NextMailingObj();
		$nextmailing->setSendTime($time->hour . ':' . $time->minute . ' ' . $time->meridian);
		$nextmailing->setFrequency('Daily');
		$nextmailing->setLastSentDate($autoresponder->nextExecution);
		$nextmailing->setDaysAllowed(json_decode($autoresponder->days));

		$autoresponder->nextExecution = $nextmailing->getNextSendTime();

		if (!$autoresponder->save()) {
			foreach ($autoresponder->getMessages() as $msg) {
				throw new Exception("Error while saving next execution of automatic campaign, {$msg}!");
			}
		}
	}


	public function send_autoresponders($mail)
	{
		$schedule = Mailschedule::findFirstByIdMail($mail->idMail);

		if($schedule) {
			$mail->status = ($mail->status == 'Draft' || $mail->status == 'draft') ? 'Scheduled' : $mail->status;
			
			if(!$mail->save()) {
				foreach ($mail->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				throw new Exception('Error saving mail in auto responder');
			}

			$schedule->confirmationStatus = 'Yes';

			if(!$schedule->save()){
				foreach ($schedule->getMessages() as $msg) {
					$this->logger->log($msg);
				}
				throw new Exception('Error saving scheduling in auto responder');
			}

			$commObj = new Communication(SocketConstants::getMailRequestsEndPointPeer());
			$commObj->sendSchedulingToParent($mail->idMail);	
		}
	}
	
	public function send_server_error_mail($autoresponder, Account $account)
	{
		try {

			$users = User::find(array(
				'conditions' => "idAccount = ?1 AND userrole = 'ROLE_WEB_SERVICES'",
				'bind' => array(1 => $account->idAccount)
			));

			$connection = $this->google_connection("www.google.com");

			$objJson = json_decode($autoresponder->content);

			$message = new AdministrativeMessages();
			foreach ($users as $user) {
				try {
					$message->createServerConnectionMessage($user->email, $connection, $autoresponder->name, $objJson->url, $account);
					$this->logger->log('Enviando correo de conexion al servidor al usuario administrador con email ' . $user->email);
					$message->sendMessage();
				}
				catch(Exception $e) {
					$this->logger->log('Error: [' . $e->getMessage() . ']');
				}
			}

			$msg = Adminmsg::findFirst(array(
				'conditions' => 'type = ?1',
				'bind' => array(1 => 'ServerConnection')
			));
			try {
				$message->createServerConnectionMessage($msg->forward, $connection, $autoresponder->name, $objJson->url, $account);
				$this->logger->log('Enviando correo de conexion al servidor al email de soporte de Sigma Movil ' . $msg->forward);
				$message->sendMessage();
			}
			catch(Exception $e) {
				$this->logger->log('Error: [' . $e->getMessage() . ']');
			}
		}
		catch(Exception $e) {
			$this->logger->log('Error: [' . $e->getMessage() . ']');
		}
	}
	
	public function send_success_mail_to_support($mail)
	{
		if($mail->idAccount == 55) {
			$users = array('ivan.barona@sigmamovil.com', 'juan.morales@sigmamovil.com');
			$message = new AdministrativeMessages();
			foreach ($users as $user) {
				$message->createTemporarySuccessMessage($user);
				$this->logger->log('Enviando correo de exito a soporte de Sigma Movil ' . $user);
				$message->sendMessage();
			}
		}
	}
	
	
	public function google_connection($url="www.google.com")  
	{  
		if( !$url ) {
			return false;  
		}
		
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$data = curl_exec($ch); 
		
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$httptime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
		
		curl_close($ch);  
		
		if($httpcode>=200 && $httpcode<400){  
			return $httptime;
		} else {  
			return false;  
		}  
	}
}