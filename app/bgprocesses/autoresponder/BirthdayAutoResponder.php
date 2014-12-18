<?php

class BirthdayAutoResponder {
	
	protected $autoresponder;
	protected $mail;
	
	function __construct() {
		$this->logger = Phalcon\DI::getDefault()->get('logger');
	}

	public function setAutoresponder(Autoresponder $autoresponder)
	{
		$this->autoresponder = $autoresponder;
	}
	
	public function setMail(Mail $mail)
	{
		$this->mail = $mail;
	}
	
	public function selectTarget()
	{
		$interpreter = new \EmailMarketing\General\Misc\InterpreterTarget();
		$interpreter->setMail($this->mail);
		$interpreter->searchContacts();
		$sql = $interpreter->getSQL();
		$statDbaseSQL = $interpreter->getStatDbaseSQL();
		$statContactlistSQL = $interpreter->getStatContactlistSQL();
		
		if ($sql != false) {
			$final_sql = $this->addClauseBirthdate($sql);
			$this->createMXC($final_sql);
			
			$this->createMXC($statDbaseSQL);
			$this->createMXC($statContactlistSQL);
			
		}
	}
	
	protected function addClauseBirthdate($origin_sql)
	{
		return $origin_sql . ' AND  c.birthDate = DATE_FORMAT(NOW(), \'%m-%d\')';
	}
	
	protected function createMXC($sql)
	{
		$executer = new \EmailMarketing\General\Misc\SQLExecuter();
		$executer->instanceDbAbstractLayer();
		$executer->setSQL($sql);
		$executer->executeAbstractLayer();
	}
}
