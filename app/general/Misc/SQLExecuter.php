<?php

namespace EmailMarketing\General\Misc;

/**
 * Executes sql sentences using Database Abstraction Layer and ModelsManager(PHQL)
 *
 * @author Will
 */
class SQLExecuter 
{
	protected $sql;
	protected $result = array();
	protected $manager;
	protected $db;

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
	
	/**
	 * Database Abstraction Layer Phalconphp
	 */
	public function instanceDbAbstractLayer()
	{
		$this->db = \Phalcon\DI::getDefault()->get('db');
	}
	
	public function queryAbstractLayer()
	{
		if (!empty($this->sql)) {
			$result = $this->db->query($this->sql);
			$this->result = $result->fetchAll();
		}
	}
	
	public function executeAbstractLayer()
	{
		if (!empty($this->sql)) {
			$result = $this->db->execute($this->sql);
			if (!$result) {
				throw new \Exception("Error while executing insert query");
			}
		}
	}
	
	/**
	 * PHQL Phalconphp
	 */
	public function instanceModelsManager()
	{
		$this->manager = \Phalcon\DI::getDefault()->get('modelsManager');
	}

	public function queryPHQL($variables)
	{
		if (!empty($this->sql)) {
			$exe = $this->manager->createQuery($this->sql);
			$this->result = $exe->execute($variables);
		}
	}
	
	public function executePHQL($variables)
	{
		if (!empty($this->sql)) {
			$this->manager->executeQuery($this->sql, $variables);
		}
	}
	
	public function getResult()
	{
		return $this->result;
	}
}
