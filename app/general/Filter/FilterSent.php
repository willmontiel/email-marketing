<?php

namespace EmailMarketing\General\Filter;
/**
 * Description of FilterSent
 *
 * @author Will
 */
class FilterSent extends FilterAbstract
{
	public function createSQL() 
	{
		$this->mc = "mc{$this->object->idMail}";
		
		if ($this->object->required) {
			if ($this->object->negative) {
				$this->required = " LEFT";
				$this->where = " {$this->mc}.idContact IS NULL ";
			}
		}
		else {
			if ($this->object->more) {
				if ($this->object->negative) {
					$this->required = " LEFT";
					$this->where = " {$this->mc}.idContact IS NULL ";
				}
				else {
					$this->required = " LEFT";
					$this->where = " {$this->mc}.idContact IS NOT NULL ";
				}
			}
			else {
				if ($this->object->negative) {
					$this->required = " LEFT";
					$this->where = " {$this->mc}.idContact IS NULL ";
				}
				else {
					//Do nothing
				}
			}
		}
	}
}