<?php
require_once '../bootstrap/phbootstrap.php';

$smart = new SmartManagmentManager();
$smart->startManagment();

class SmartManagmentManager
{
	protected $logger;
	protected $smarts;
	protected $smart;
	protected $time;
	protected $rules = array();
	protected $conditions = array();
	protected $SQLRules = "";
	protected $SQLRulesArray = array();
	protected $points = 0;
	protected $accounts;
	protected $account = null;

	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function startManagment()
	{
		$this->searchSmartManagment();
		if (count($this->smarts) > 0) {
			foreach ($this->smarts as $smart) {
				$this->smart = $smart;
				$this->time = strtotime("{$smart->time}");
				$this->validateAccount();
				$this->searchRules();
				$this->convertRulesInSQL();
				$this->executeQuerys();
				$this->sendCommunications();
				unset($this->smart);
			}
		}
	}
	
	private function executeQuerys()
	{
		if ($this->smart->logicOperator == 'and') {
			$this->SQLRules = " AND " . implode(' AND ', $this->conditions);
			$this->searchMatches();
			$this->scoreAccounts();
			
		}
		else if ($this->smart->logicOperator == 'or') {
			foreach ($this->conditions as $condition) {
				$this->SQLRules = " AND {$condition}";
				$this->searchMatches();
				$this->scoreAccounts();
			}
		}
	}


	private function searchSmartManagment()
	{
		$this->smarts = Smartmanagment::find();
	}
	
	private function searchRules()
	{
		$this->rules = \Rule::find(array(
			'conditions' => 'idSmartmanagment = ?1',
			'bind' => array(1 => $this->smart->idSmartmanagment)
		));
	}		
	
	
	private function convertRulesInSQL()
	{
		if (count($this->rules) > 0) {
			foreach ($this->rules as $rule) {
				$data = json_decode($rule->rule);
				if (is_array($data)) {
					$part1 = "";
					$part2 = "";
					$part3 = "";
					
					foreach ($data as $d) {
						switch ($d->type) {
							case 'index-rule':
								$part1 = $this->validateIndexRule($d->value);
								break;
							
							case 'operator-rule':
								$part2 = $d->value;
								break;
							
							case 'condition-rule':
								if ($d->class == '%') {
									$part3 = "((messagesSent*{$d->value})/100)";
								}
								else if ($d->class == '#') {
									$part3 = $d->value;
								}
								break;
							
							case 'points-rule':
								if ($d->points == 'true') {
									$this->points = $d->value;
								}
								break;
							
							default :
								break;
						}
					}
					
					$condition = " {$part1} {$part2} {$part3}";
					$this->conditions[] = $condition; 
				}
			}
		}
	}
	
	private function validateIndexRule($index)
	{
		$part1 = "";
		switch ($index) {
			case 'opens':
				$part1 = "totalContacts";
				break;
			
			case 'bounced':
				$part1 = "bounced";
				break;
			
			case 'unsubscribed':
				$part1 = "unsubscribed";
				break;
			
			case 'spam':
				$part1 = "spam";
				break;

			default:
				break;
		}
		
		return $part1;
	}
	
	private function searchMatches()
	{
		$sql = "SELECT idAccount
				FROM mail
				WHERE status = 'Sent'
					AND finishedon >= {$this->time}
				{$this->SQLRules}";
			
//		$this->logger->log("SQL: {$sql}");		
				
		$db = Phalcon\DI::getDefault()->get('db');
		$result = $db->query($sql);
		$this->accounts = $result->fetchAll();
	}
	
	private function scoreAccounts()
	{
		foreach ($this->accounts as $account) {
			if ($this->account != null) {
				$score = \Score::findFirstByIdAccount($account->idAccount);
			
				$db = Phalcon\DI::getDefault()->get('db');
				$db->begin();

				if (!$score) {
					$score = new \Score();
					$score->idAccount = $account->idAccount;
					$score->score = 0;
					$score->createdon = time();
				}

				$score->score += $this->points;
				$score->updatedon = time();

				if (!$score->save()) {
					foreach ($score->getMessages() as $msg) {
						$db->rollback();
						throw new Exception("Error while scoring account... {$msg}");
					}
				}

				$scorehistory = new \Scorehistory();
				$scorehistory->idAccount = $account->idAccount;
				$scorehistory->idSmartmanagment = $this->smart->idSmartmanagment;
				$scorehistory->score = $this->points;
				$scorehistory->createdon = time();

				if (!$scorehistory->save()) {
					foreach ($scorehistory->getMessages() as $msg) {
						$db->rollback();
						throw new Exception("Error while scoring account history... {$msg}");
					}
				}

				$db->commit();
			}
		}
	}
	
	private function validateAccount()
	{
		$account = Account::findFirst(array(
			'conditions' =>  'idAccount = ?1',
			'bind' => array(1 => $this->smart->idAccount)
		));
		
		if ($account) {
			$this->account = $account;
		}
	}
	
	private function sendCommunications()
	{
//		$mailWrapper = new MailWrapper();
//		$mailWrapper->setAccount($this->account);
//		$mailWrapper->setContent($content);
//		$mailWrapper->processDataForMail();
//		$mailWrapper->saveMail();
	}
}