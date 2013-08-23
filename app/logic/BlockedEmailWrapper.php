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
	
	
	public function convertBlockedEmailList($Blockedemail)
	{
		$object = array();
		$object['idBlockedEmail'] = $Blockedemail->idBlockedEmail;
		$object['idEmail'] = $Blockedemail->idEmail;
		$object['blockedReason'] = $Blockedemail->blockedReason;
		$object['BlockedDate'] = $Blockedemail->blockedDate;
		$object['email'] = $Blockedemail->email;
		return $object;
	}
	public function findBlockedEmailList(Account $account)
	{
		$modelManager = Phalcon\DI::getDefault()->get('modelsManager');
		
		$idAccount = $account->idAccount;
		
		$parameters = array('idAccount' => $idAccount);
		
		$querytxt = "SELECT COUNT(*) AS cnt FROM Blockedemail JOIN Email ON Blockedemail.idEmail = Email.idEmail WHERE idAccount = :idAccount:";
	
		$query2 = $modelManager->createQuery($querytxt);
        $result = $query2->execute($parameters)->getFirst();
				
		$total = $result->cnt;
		
		$querytxt2 = "SELECT Blockedemail.idBlockedEmail, Blockedemail.idEmail, Blockedemail.blockedReason, Blockedemail.blockedDate, Email.email FROM Blockedemail JOIN Email ON Blockedemail.idEmail = Email.idEmail WHERE idAccount = :idAccount:";
	
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
		
		$this->pager->setRowsInCurrentPage(count($Blocked));
		$this->pager->setTotalRecords($total);
		return array('blockeds' => $blocked, 
					 'meta' => $this->pager->getPaginationObject()
				) ;
	}
}