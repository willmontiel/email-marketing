<?php

namespace EmailMarketing\General\Filter;

/**
 * Description of FilterOpening
 *
 * @author Will
 */
class FilterOpening extends FilterAbstract
{
	public function createSQL() 
	{
		$this->alias = "mc{$this->object->id}{$this->key}";
		$this->equals = ($this->object->negative ? "=" : "!=");
		
		if (!$this->object->required) {
			if ($this->object->more) {
				$this->required = " LEFT";
				$this->where = " {$this->alias}.idContact IS NOT NULL ";
			}
		}
		
		$this->condition = " AND {$this->alias}.opening {$this->equals} 0";
		
		$this->createFrom();
	}
}
