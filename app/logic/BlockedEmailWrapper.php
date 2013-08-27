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
	
	public function validateEmailBelongsToAccount($idEmail, $Dbases)
	{
		return true;
	}

	public function validateBlockedEmailData($contents)
	{
		if (!isset($contents->email)) {
			throw new InvalidArgumentException('No has enviado un email');
		}
		else {
			$email = Email::findFirstByEmail($contents->email);
			if(!$email) {
				throw new InvalidArgumentException('El email enviado no existe');
			} elseif($email->blocked != 0) {
					throw new InvalidArgumentException('Este email ya se encuentra bloqueado');
			}
			return $this->addEmailToBlockedList($contents, $email);
		}
	}

	public function convertBlockedEmailList($Blockedemail)
	{
		$object = array();
		$object['id'] = $Blockedemail->idBlockedemail;
		$object['email'] = $Blockedemail->email;
		$object['blocked_reason'] = $Blockedemail->blockedReason;
		$object['blocked_date'] = $Blockedemail->blockedDate;

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
			return $blocked;
		} else {
			throw new InvalidArgumentException('Ha ocurrido un error, por favor contacta con tu administrador');
		}	
	}
	
	//esta funcion remueve un email de la lista de bloqueo
	public function removeEmailFromBlockedList($idBlockedemail)
	{
		$removeEmail = Blockedemail::findFirst(array(
				"conditions" => "idBlockedemail = ?1",
				"bind"       =>array(1 => $idBlockedemail)
			)
		);
		
		if(!$removeEmail) {
			throw new InvalidArgumentException('El email no se encuentra en la lista global de bloqueo');
		}
		
		else {
			$emailUnblock = Email::findFirst(array(
					"conditions" => "idEmail = ?1",
					"bind"		 => array(1 => $removeEmail->idEmail)
				)
			);
			
			$emailUnblock->blocked = 0;
			
			if(!$emailUnblock->save()) {
				throw new InvalidArgumentException('El email no existe');
			}
			
			$removeEmail->delete();
			return array("Se ha desbloqueado el email" => $msj);
		}
	}
}
