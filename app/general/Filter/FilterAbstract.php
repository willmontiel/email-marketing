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
		return $this->from;
	}
	
	public function getWhere()
	{
		return $this->where;
	}
	
	public function processConditions()
	{
		if ($this->object->required) {
			$this->required = "";
			$this->where = "";
		}
		else {
			$this->required = ($this->object->more ? " LEFT" : "");
			$this->where = "{$this->mc} mc{$i}.idContact IS {$neg} NULL";
		}
	}
}
