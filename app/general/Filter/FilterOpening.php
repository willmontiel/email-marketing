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
		$this->mc = "mc{$this->object->idMail}";
		$this->equals = ($this->object->negative ? "=" : "!=");
		
		if (!$this->object->required) {
			if ($this->object->more) {
				$this->required = " LEFT";
				$this->where = " {$this->mc}.idContact IS NOT NULL ";
			}
		}
		
		$this->condition = " AND {$this->mc}.opening {$this->equals} 0";
	}
}
