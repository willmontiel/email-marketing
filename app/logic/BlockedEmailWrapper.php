<?php
class BlockedEmailWrapper
{
	protected $pager;
	protected $_di;

	public function __construct()
	{
		$this->pager = new PaginationDecorator();
	}
	
	public function setPager(PaginationDecorator $p)
	{
		$this->pager = $p;
	}
	
	/**
	 * Esta funcion valida que el id de la lista de bloqueo pertenezca a un base de datos de la cuenta en 
	 * la que se encuentra registrado el usuario que efectua la accion
	 */
	public function removeEmailFromBlockedList(Account $account, $idBlockemail)
	{
		$bemail = Blockedemail::findFirst($idBlockemail);
		
		if (!$bemail || $bemail->email->account != $account) {
			throw new Exception('Error');
		}
		$bemail->email->blocked = 0;
		$bemail->email->save();
		
		$bemail->delete();
	}

	//esta funcion valida que el email a bloquear exista y que no se encuentre bloqueado
	public function addBlockedEmail($contents, $account)
	{
		if (!\filter_var($contents->email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException('La direccion [' . $contents->email . '] no es una direccion de correo valida!');
		}
		else {
			if (!isset($contents->email)) {
				throw new InvalidArgumentException('No has enviado un email');
			}
			else {
			
				$email = Email::findFirst(
						array(
							'conditions' => 'email = ?1 AND idAccount = ?2',
							'bind' => array(
								1 => $contents->email, 
								2 => $account->idAccount
							)
						)
				);
				if(!$email) {
	//				throw new InvalidArgumentException('El email enviado no existe');
					$blockedEmail = $this->createBlockedEmail($contents, $account);
				} 
				else if($email->blocked != 0) {
					throw new InvalidArgumentException('Este email ya se encuentra bloqueado');
				}

				else {
					$blockedEmail = $this->addEmailToBlockedList($contents, $email);
				}
			}
		}
		return $blockedEmail;
	}

	//Esta funccion crea un email ya bloqueado, en caso de que no exista.
	public function createBlockedEmail($contents, $account)
	{
		$createEmail = new Email();
		
		list($user, $edomain) = preg_split("/@/", $contents->email, 2);
		
		$domain = Domain::findFirstByName($edomain);
		
		if (!$domain) {
			$domain = new Domain();
			$domain->name = $edomain;
			if (!$domain->save()) {
				$errmsg = $domain->getMessages();
				$msg = '';
				foreach ($errmsg as $err) {
					$msg .= $err . PHP_EOL;
				}
				throw new \Exception('Error al crear el dominio [' . $edomain . ']: >>' . $msg . '<<');
			}
		}
		
		$idAccount = $account->idAccount;
		
		$createEmail->idAccount = $idAccount;
		$createEmail->domain = $domain;
		$createEmail->email = $contents->email;
		$createEmail->bounced = 0;
		$createEmail->spam = 0;
		$createEmail->blocked = time();

		if (!$createEmail->save()) {
			$errmsg = $createEmail->getMessages();
			$msg = '';
			foreach ($errmsg as $err) {
				$msg .= $err . PHP_EOL;
			}
			throw new \Exception('Error al crear el email [' . $contents->email . ']: >>' . $msg . '<<');
		}
		
		else {
			$email = Email::findFirst(
					array(
						'conditions' => 'email = ?1 AND idAccount = ?2',
						'bind' => array(
							1 => $contents->email, 
							2 => $account->idAccount
						)
					)
			);
			$blockedEmail = $this->addEmailToBlockedList($contents, $email);
			
			return $blockedEmail;
		}
		
	}
	
	//convierte los valores de la busqueda en en json
	public function convertBlockedEmailList($Blockedemail)
	{
		$object = array();
		$object['id'] = $Blockedemail->idBlockedemail;
		$object['email'] = $Blockedemail->email;
		$object['blocked_reason'] = $Blockedemail->blockedReason;
		$object['blocked_date'] = date('d/m/Y H:i', $Blockedemail->blockedDate);

		return $object;
	}
	
	//Funcion que busca todos los datos de los emails y las listas de bloqueo que coincidan con la cuenta
	//y se lss envía a ember paginados en un arreglo para que pueda mostrarlos al usuario en el UI
	public function findBlockedEmailList(Account $account)
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$idAccount = $account->idAccount;
		
		$parameters = array('idAccount' => $idAccount);
		
		$querytxt = "SELECT COUNT(*) AS cnt FROM Blockedemail JOIN Email ON Blockedemail.idEmail = Email.idEmail WHERE idAccount = :idAccount:";
	
		$query2 = $modelManager->createQuery($querytxt);
        $result = $query2->execute($parameters)->getFirst();
				
		$total = $result->cnt;
		
		$querytxt2 = "SELECT Blockedemail.idBlockedemail, Blockedemail.idEmail, Blockedemail.blockedReason, Blockedemail.blockedDate, Email.email FROM Blockedemail JOIN Email ON Blockedemail.idEmail = Email.idEmail WHERE idAccount = :idAccount:";
	
		if ($this->pager->getRowsPerPage() != 0) {
			$querytxt2 .= ' LIMIT ' . $this->pager->getRowsPerPage() . ' OFFSET ' . $this->pager->getStartIndex();
		}
        $query = $modelManager->createQuery($querytxt2);
		$blockedEmails = $query->execute($parameters);
		
		$blocked = array();
		
		if ($blockedEmails) {
			foreach ($blockedEmails as $blockedEmail) {
				$blocked[] = $this->convertBlockedEmailList($blockedEmail);
			}
		}
		
		$this->pager->setRowsInCurrentPage(count($blocked));
		$this->pager->setTotalRecords($total);
		return array('blockedemails' => $blocked, 
					 'meta' => $this->pager->getPaginationObject()
				) ;
	}
	
	public function addEmailToBlockedList($contents, Email $email)
	{
		$blocked = new Blockedemail();
		
		$blocked->idEmail = $email->idEmail;
		$blocked->blockedReason = $contents->blocked_reason;
		$blocked->blockedDate = time();
		
		if($blocked->save()){
			$email->blocked = time();
			$email->save();
			$blocked->email = $email->email;
			
			$updateContact = array('unsubscribed' => time());
			$wrapper = new ContactWrapper();
		
			$wrapper->updateContact($email->idEmail, $updateContact);
			
			$blockedJson = $this->convertBlockedEmailList($blocked);
			return $blockedJson;
		} 
		else {
			throw new InvalidArgumentException('Se presento un error en la creacion del bloqueo!');
		}	
	}
	
}
