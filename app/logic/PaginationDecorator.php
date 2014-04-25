<?php
/**
 * Description of PaginationDecorator
 *
 * @author Will
 */
class PaginationDecorator {
	protected $total;
	protected $rowsperpage;
	protected $currentpage;
	protected $rows;
	
	const START_PAGE = 1;
	const DEFAULT_LIMIT = 2;
	
	public function __construct()
	{
		$this->total = 0;
		$this->rowsperpage = self::DEFAULT_LIMIT;
		$this->currentpage = self::START_PAGE;
		$this->rows = 0;
	}

	public function setTotalRecords($t)
	{
		$this->total = $t;
	}
	
	public function setRowsPerPage($r)
	{
		$this->rowsperpage = $r;
	}
	
	public function setCurrentPage($i)
	{
		$this->currentpage = $i;
	}

	public function getCurrentPage()
	{
		return $this->currentpage;
	}
	
	public function getRowsPerPage()
	{
		return $this->rowsperpage;
	}
	
	public function setRowsInCurrentPage($r)
	{
		$this->rows = $r;
	}
	
	public function getStartIndex()
	{
            return ceil(($this->currentpage -1 )*$this->rowsperpage);
	}
	
	public function getTotalPages()
	{
		return ceil($this->total / $this->rowsperpage);
	}

	public function getTotalRecords()
	{
		return $this->total;
	}
	
	public function getPaginationObject()
	{
		//array( 'pagination' => array('page' => $page, 'limit' => $limit, 'total' => $total, 'availablepages' => $availablepages)
		$pobject = array(
			'page' => $this->currentpage,
			'limit' => $this->rowsperpage,
			'total' => $this->total,
			'rows'  => $this->rows,
			'availablepages' => ceil($this->total / $this->rowsperpage),			
		);
		
		return array('pagination' => $pobject);
	}
}
