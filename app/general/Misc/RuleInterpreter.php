<?php

namespace EmailMarketing\General\Misc;

class RuleInterpreter
{
	protected $logger;
	protected $smart;
	protected $object;
	protected $rules = array();
	protected $SQLRulesArray = array();
	protected $SQLRules = "";
	protected $points = 0;
	protected $pre_mails = array();

	public function __construct() 
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setSmart(\Smartmanagment $smart)
	{
		$this->smart = $smart;
	}
	
	public function setObject($object) 
	{
		$this->object = $object;
	}
	
	public function validateObject()
	{
		
	}	
	
	


	public function searchMails() 
	{
		$time = strtotime("-{$this->smart->time}");
		
		$this->pre_mails = \Mail::find(array(
			"conditions" => "status = 'Sent' AND finishedon >= ?1", 
			"bind"  => array(1 => $time)
		));
		
		if (count($this->pre_mails) > 0) {
			$this->searchMatches();
		}
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
				}
			}
		}
	}
	
	private function validateIndexRule($index)
	{
		$part1 = "";
		switch ($index) {
			case 'open':
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
		
	}
}