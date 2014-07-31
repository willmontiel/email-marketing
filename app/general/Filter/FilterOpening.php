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
		
		$this->processConditions();
		
		switch ($this->object->negative) {
			case true;
				$equals = '=';
				
				if ($this->object->required) {
					$this->required = "";
					$this->where = "";
				}
				else {
					if ($this->object->more) {
						$this->required = " LEFT";
						$this->where = "{$this->mc}.idContact IS NOT NULL";
					}
					else {
						
					}
				}
				break;
			
			case false:
				$equals = '!=';
				break;
		}
		
		$this->from = "{$this->required} JOIN mxc AS {$mc} ON ({$mc}.idContact = c.idContact AND {$mc}.idMail = {$this->object->idMail} AND {$mc}.opening {$equals} 0) ";
	}
}
