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
	
	public function setContactlist($x)
	{
		$this->contactlists = $x;
	}
	
	public function load()
	{
//		foreach ($this->contactlists as $list) {
//			$list = array();
//			\Phalcon\DI::getDefault()->get('logger')->log("id: " . $list->idContactlist);
//			$list['id'] = $list->idContactlist;
//			$list['name'] = $list->name;
//			
//			$this->rows[] = $list;
//		}
		$this->rows = $this->contactlists;
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
}
