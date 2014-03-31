<?php
class ProcessMail
{
	public function setAccount(Account $account)
	{
		$this->account = $account;
	}

	public function deleteMail($idMail)
	{
		$log = Phalcon\DI::getDefault()->get('logger');
		$mail = Mail::findFirst(array(
			"conditions" => "(idMail = ?1 AND idAccount = ?2)",
			"bind" => array(1 => $idMail,
							2 => $this->account->idAccount)
		));
		
		if (!$mail) {
			throw new InvalidArgumentException("No se ha encontrado el correo, por favor verifique la información");
		}
		
		switch ($mail->status) {
			case 'Sending':
			case 'Paused':
				throw new InvalidArgumentException("Lo sentimos pero el correo esta siendo procesado");
				break;
			case 'Sent':
			case 'Cancelled':
				$this->markedAsDeleted($mail);
				break;
			default :
				if (!$mail->delete()) {
					foreach ($mail->getMessages() as $msg) {
						$log->log($msg);
					}
					throw new InvalidArgumentException("Lo sentimos pero el correo no se pudo eliminar");
				}
				break;
		}
	}
	
	public function markedAsDeleted(Mail $mail) 
	{
		$log = Phalcon\DI::getDefault()->get('logger');
		$mail->deleted = time();
		
		if (!$mail->save()) {
			foreach ($mail->getMessages() as $msg) {
				$log->log($msg);
			}
			throw new InvalidArgumentException("Lo sentimos pero el correo no se pudo eliminar");
		}
	}
}