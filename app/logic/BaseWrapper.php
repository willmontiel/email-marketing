<?php


class BaseWrapper 
{
	protected $pager;
	protected $account;
	
	protected $fieldErrors;

	public function __construct()
	{
		$this->pager = new PaginationDecorator();
		$this->fieldErrors = array();
	}
	
	public function setPager(PaginationDecorator $p)
	{
		$this->pager = $p;
	}
	
	public function setAccount(Account $account) {
		$this->account = $account;
	}
	
	public function getFieldErrors()
	{
		return $this->fieldErrors;
	}
	
	protected function addFieldError($fieldname, $errormsg) 
	{
		if (isset($this->fieldErrors[$fieldname])) {
			$this->fieldErrors[$fieldname][] = $errormsg;
		}
		else {
			$this->fieldErrors[$fieldname] = array($errormsg);
		}
	}

}
