<?php

namespace EmailMarketing\General\ModelAccess;

/**
 * 
 */
class ContactSearchFilter
{
	public $filter;
	
	public function __construct($filter) 
	{	
		if (trim($filter) === '') {
			$filter = 'all';
		}
		$this->filter = array($filter, 0);
	}
	
	/**
	 * Retorna un arreglo con el campo para crear el sql final en la búsqueda de contactos Ej:
	 * filter = array('blocked', 0);
	 * @return array
	 */
	public function getFilter()
	{
		return $this->filter;
	}
}
