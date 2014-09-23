<?php
class FlashMessages 
{	
	protected $messages = array();
	
	private function searchMessages()
	{
		$messages = Flashmessage::find(array(
			'conditions' => 'start <= ?1 AND end >= ?2',
			'bind' => array(1 => time(),
							2 => time())
		));
		
		if (count($messages) > 0) {
			$this->messages = array();
			foreach ($messages as $msg) {
				if ($msg->accounts == 'all' || $msg->accounts == null) {
					$this->messages[] = $msg;
				}
				else {
					$idUser = Phalcon\DI::getDefault()->get('session')->get('userid');
					$user = User::findFirst(array(
						'conditions' => 'idUser = ?1',
						'bind' => array(1 => $idUser)
					));
					$accounts = json_decode($msg->accounts);
					foreach ($accounts as $account) {
						if ($account == $user->idAccount) {
							$this->messages[] = $msg;
						}
					}
				}
			}
		}
	}
	
	public function getLength()
	{
		$this->searchMessages();
		return count($this->messages);
	}
	
	public function getMessages() 
	{
		return $this->messages;
	}
}
