<?php

namespace EmailMarketing\General\Filter;
/**
 * Description of FilterClicks
 *
 * @author Will
 */
class FilterClicks extends FilterAbstract
{
	public function createSQL() 
	{
		$this->alias = "ml{$this->object->id}{$this->key}";
		
		if ($this->object->required) {
			if ($this->object->more) {
				if ($this->object->negative) {
					
				}
				else {
					//Do nothing
				}
			}
			else {
				if ($this->object->negative) {
					
				}
				else {
					//Do nothing
				}
			}
		}
		else {
			if ($this->object->more) {
				if ($this->object->negative) {
					
				}
				else {
					$this->required = " LEFT";
					$this->where = " {$this->alias}.idContact IS NOT NULL ";
				}
			}
			else {
				if ($this->object->negative) {
					
				}
				else {
					//Do nothing
				}
			}
		}
		
		
		
		$this->createFrom();
	}
}