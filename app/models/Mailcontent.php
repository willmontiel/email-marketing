<?php
class Mailcontent extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail");
		
	}
}