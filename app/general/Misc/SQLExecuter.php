<?php

namespace EmailMarketing\General\Misc;

/**
 * Executes sql sentences using Database Abstraction Layer
 *
 * @author Will
 */
class SQLExecuter 
{
	protected $sql;
	protected $result = array();

	public function __construct()
	{
		$this->logger = \Phalcon\DI::getDefault()->get('logger');
	}
	
	public function setSQL($sql)
	{
		$sql = trim($sql);
		if (empty($sql)) {
			throw new \InvalidArgumentException("invalid SQL!!");
		}
		
		$this->sql = $sql;
	}
	
	public function executeSelectQuery()
	{
		if (!empty($this->sql)) {
			$db = \Phalcon\DI::getDefault()->get('db');
			$result = $db->query($this->sql);
			$this->result = $result->fetchAll();
		}
	}
	
	public function executeQuery()
	{
		if (!empty($this->sql)) {
			$db = \Phalcon\DI::getDefault()->get('db');
			$result = $db->execute($this->sql);
			if (!$result) {
				throw new \Exception("Error while executing insert query");
			}
		}
	}
	
	public function getResult()
	{
		return $this->result;
	}
}
