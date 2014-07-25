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
		$context = new ZMQContext();

		$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
		$requester->connect(SocketConstants::getMailRequestsEndPointPeer());
		
		$requester->send(sprintf("%s $idMail $idMail", 'Play-Task'));
		$response = $this->requester->recv(ZMQ::MODE_NOBLOCK);
	}
}