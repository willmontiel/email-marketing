<?php

namespace EmailMarketing\General\ModelAccess;
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContactlistSet implements \EmailMarketing\General\ModelAccess\DataSource
{
	protected $contactlists;
	protected $name;
	protected $rows = array();

	public function __construct() {
		$this->rows = array();
		$this->contactlists = null;
	}
	
	public function setContactlist(/*\Contactlist*/ $contactlist)
	{
		$this->contactlists = $contactlist;
	}
	
	public function load()
	{
		foreach ($this->contactlists as $list) {
			$lists = array();
			\Phalcon\DI::getDefault()->get('logger')->log("id: " . $list->idContactlist);
			$lists['id'] = $list->idContactlist;
			$lists['name'] = $list->name;
			
			$this->rows[] = $lists;
		}
		$this->name = 'lists';
	}

	public function getName()
	{
		return $this->name;
	}
	
	public function getRows()
	{
		return $this->rows;
	}
	
	public function getCurrentPage()
	{
		
	}
	
	public function getTotalPages()
	{
		
	}
	
	public function getTotalRecords()
	{
		
	}
	
	public function getRowsPerPage() 
	{
		
	}
}
