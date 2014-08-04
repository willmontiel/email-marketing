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
		$this->contactAlias = "c";
		
		if ($this->object->negative) {
			$mc = "mc{$this->object->idMail}{$this->key}";
			$this->contactAlias = $mc;
			$this->mxcJoin = " JOIN mxc AS {$mc} ON ($mc.idContact = c.idContact AND {$mc}.idMail = {$this->object->idMail})";
			$this->required = " LEFT";
			$this->where = " {$this->alias}.idContact IS NULL ";
		}
		else {
			if (!$this->object->required) {
				if ($this->object->more) {
					if (!$this->object->negative) {
						$this->required = " LEFT";
						$this->where = " {$this->alias}.idContact IS NOT NULL ";
					}
				}
			}
		}
		
		$this->createFrom();
	}
}