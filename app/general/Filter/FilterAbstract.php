<?php

namespace EmailMarketing\General\Filter;

/**
 * Description of FilterAbstract
 *
 * @author Will
 */
abstract class FilterAbstract 
{
	protected $key = "";
	protected $from = "";
	protected $where = "";
	protected $object;
	protected $equals = "";
	protected $required = "";
	protected $alias = "";
	protected $condition = "";
	protected $mxcJoin = "";
	protected $contactAlias = "";

	public function setObject($obj)
	{
		if (empty($obj)) {
			throw new \InvalidArgumentException("Object setted is null or empty!");
		}
		$this->object = $obj;
	}

	public function setKey($key)
	{
		$this->key = $key;
	}
	
	abstract function createSQL();
	
	public function getFrom()
	{
		return $this->from;
	}
	
	public function getWhere()
	{
		return $this->where;
	}
	
	public function createFrom()
	{
		switch ($this->object->type) {
			case 'mail':
				$this->from = "{$this->required} JOIN mxc AS {$this->alias} ON ({$this->alias}.idContact = c.idContact AND {$this->alias}.idMail = {$this->object->id}{$this->condition}) ";
				break;
			
			case 'click':
				$this->from = "{$this->mxcJoin}{$this->required} JOIN mxcxl AS {$this->alias} ON ({$this->alias}.idContact = {$this->contactAlias}.idContact AND {$this->alias}.idMailLink = {$this->object->id} AND {$this->alias}.idMail = {$this->object->idMail}) ";
				break;
		}
	}
}
