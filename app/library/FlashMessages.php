<?php
class FlashMessages 
{	
	public function getMessages() 
	{
		$messages = Flashmessage::find(array(
			'conditions' => 'start <= ?1 AND end >= ?2',
			'bind' => array(1 => time(),
							2 => time())
		));
		
		if (!$messages) {
			$message = array();
			foreach ($messages as $msg) {
				if ($msg->accounts == 'all' || $msg->accounts == null) {
					$message[] = $msg;
				}
				else {
					$idUser = Phalcon\DI::getDefault()->get('session')->get('userid');
					$user = User::findFirst(array(
						'conditions' => 'idUser = ?1',
						'bind' => array(1 => $idUser)
					));
					$accounts = json_decode($msg->accounts);
//					Phalcon\DI::getDefault()->get('logger')->log("Account: " . print_r($accounts, true));
					foreach ($accounts as $account) {
//						Phalcon\DI::getDefault()->get('logger')->log("Account: " . $account);
						if ($account == $user->idAccount) {
							$message[] = $msg;
						}
						else {
							Phalcon\DI::getDefault()->get('logger')->log("No hay flash messages para esta cuenta");
						}
					}
				}
			}
		}
		else {
			$message = false;
		}
		
		return $message;
	}
}
