<?php
class Mail extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		$this->hasMany("idMail", "Mxc", "idMail");
		
		$this->hasOne("idMail", "Mailcontent", "idMail");
		
	}
}