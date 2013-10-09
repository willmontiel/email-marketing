<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Email;
class Mail extends Modelbase
{
	public function initialize()
	{
		$this->hasMany("idMail", "Mxc", "idMail");
		
		$this->hasOne("idMail", "Mailcontent", "idMail");
		
	}
}