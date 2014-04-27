<?php
class AuditTrace extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		
	}
	public function getSource()
	{
		return "audittrace";
	}	

	public static function createAuditTrace($user, $result, $operation, $description, $date, $ip)
	{
		try {
			$audit = new self();
		
			if (!$user) {
				$audit->idUser = 0;
			}
			else {
				$audit->idUser = $user->idUser;
			}
			$audit->result = $result;
			$audit->operation = $operation;
			$audit->description = $description;
			$audit->date = $date;
			$audit->ip = ip2long($ip);

			if (!$audit->save()) {
				$message = 'Error while saving audit trace' . PHP_EOL;
				foreach ($audit->getMessages() as $msg) {
					$message .= " - {$msg}" . PHP_EOL;
				}
				self::saveInLog($user, $result, $operation, $description, $date, $ip, $msg);
			}
		}
		catch (Exception $e) {
			$msg = "Exception while saving audit trace" . PHP_EOL;
			$msg .= $e;
			self::saveInLog($user, $result, $operation, $description, $date, $ip, $msg);
		}
	}
	
	protected static function saveInLog($user, $result, $operation, $description, $date, $ip, $msg)
	{
		$logger = Phalcon\DI::getDefault()->get('logger');
		
		$logger->log("***************************************************************************************");
		$logger->log("***************************************************************************************");
		$logger->log("{$msg}");
		$logger->log("***************************************************************************************");
		$logger->log("***************************************************************************************");
		$logger->log("User: {$user->idUser}/{$user->username}");
		$logger->log("Result: {$result}");
		$logger->log("Operation: {$operation}");
		$logger->log("Desc: {$description}");
		$logger->log("Date: " . date('d/m/Y H:i', $date));
		$logger->log("IP: {$ip}");
		$logger->log("***************************************************************************************");
		$logger->log("***************************************************************************************");
	}
}