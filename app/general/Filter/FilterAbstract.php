<?php

namespace EmailMarketing\General\Filter;

/**
 * Description of FilterAbstract
 *
 * @author Will
 */
abstract class FilterAbstract 
{
	protected $from = "";
	protected $where = "";
	protected $object;
	protected $equals = "";
	protected $required = "";
	protected $mxc = "mxc";
	protected $condition = "";

	public function setObject($obj)
	{
		if (empty($obj)) {
			throw new \InvalidArgumentException("Object setted is null or empty!");
		}
		$this->object = $obj;
	}

	abstract function createSQL();
	
	public function getFrom()
	{
		$this->from = "{$this->required} JOIN mxc AS {$this->mc} ON ({$this->mc}.idContact = c.idContact AND {$this->mc}.idMail = {$this->object->idMail}{$this->condition}) ";
		return $this->from;
	}
	
	public function getWhere()
	{
		return $this->where;
	}
}
