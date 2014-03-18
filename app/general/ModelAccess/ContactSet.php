<?php

namespace EmailMarketing\General\ModelAccess;

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContactSet implements \EmailMarketing\General\ModelAccess\DataSource
{
	protected $searchCriteria;
	protected $account;
	protected $dbase;
	protected $contactlist;
	protected $segment;
	protected $paginator;

	public function setSearchCriteria(\EmailMarketing\General\ModelAccess\ContactSearchCriteria $search)
	{
		$this->searchCriteria = $search;
	}
	
	public function setAccount(\Account $account)
	{
		$this->account = $account;
	}
	
	public function setDbase(\Dbase $dbase)
	{
		$this->dbase = $dbase;
	}
	
	public function setContactListID(\Contactlist $contactlist)
	{
		$this->contactlist = $contactlist;
	}
	
	public function setSegmentID(\Segment $segment)
	{
		$this->segment = $segment;
	}
	
	public function setPaginator(\PaginationDecorator $paginator)
	{
		$this->paginator = $paginator;
	}
	
	public function load()
	{
		
	}
	
	private function createCoreQuery()
	{
		
	}
	
	private function createQuery()
	{
		
	}
	
	/**
	 * Metodos de la interface DataSource
	 */
	public function getName();
	public function getRows();
	public function getCurrentPage();
	public function getTotalPages();
	public function getTotalRecords();
}
