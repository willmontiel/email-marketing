<?php

namespace EmailMarketing\General\Ember;

use EmailMarketing\General\ModelAccess\DataSource;

class RESTResponse 
{
	protected $sources;
	protected $code;
	protected $status;
	protected $primary;

	public function __construct() {
		$this->sources = array();
		$this->primary = null;
	}

	public function addDataSource(DataSource $dataSource, $primary = true)
	{
		$this->sources[] = $dataSource;
		if ($primary) {
			$this->primary = $dataSource;
		}
	}
	
	public function getRecords()
	{
		$contacts = array();
		
		foreach ($this->sources as $ds) {
			$contacts[$ds->getName()] = $ds->getRows();
		}
		
		$pagination = array('pagination' => array(
			'page' => $this->primary->getCurrentPage(),
			'limit' => $this->primary->getRowsPerPage(),
			'total' => $this->primary->getTotalRecords(),
			'rows' => $this->primary->getRowsPerPage(),
			'availablepages' => $this->primary->getTotalPages(),			
		));
		
		$contacts['meta'] = $pagination;
		return $contacts;
	}
}
