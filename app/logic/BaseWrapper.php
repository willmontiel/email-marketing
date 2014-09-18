<?php


class BaseWrapper 
{
	protected $pager;
	protected $account;
	protected $fieldErrors;
	protected $messageError;
	
	protected $_replace_accents = array(
								'&aacute;', 
								'&eacute;', 
								'&iacute;', 
								'&oacute;', 
								'&uacute;', 
								'&agrave;', 
								'&egrave;', 
								'&Aacute;', 
								'&Eacute;', 
								'&Iacute;', 
								'&Oacute;', 
								'&Uacute;',
								'&ntilde;',
								'&Ntilde;',
								'&iquest;',
								'&iexcl;'
							  );
	
	protected $_search_accents = array(
									'á', 
									'é', 
									'í', 
									'ó', 
									'ú', 
									'à', 
									'è', 
									'Á', 
									'É', 
									'Í', 
									'Ó', 
									'Ú',
									'ñ',
									'Ñ',
									'¿',
									'¡'
								);
	
	protected $_popularDomains = array(
		'hotmail.es',
		'hotmail.com',
		'live.com',
		'aol.com',
		'yahoo.com',
		'yahoo.es',
		'gmail.com'
	);


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
	
	protected function addMessageError($key, $message, $code)
	{
		$this->messageError = new stdClass();
		$this->messageError->key = $key;
		$this->messageError->message = $message;
		$this->messageError->code = $code;
	}
	
	public function getResponseMessageForEmber()
	{
		return $this->messageError;
	}

}
