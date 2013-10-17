<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Mailcontent extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail");
		
		$this->useDynamicUpdate(true);
	}
	
	public function validation()
    {
		$this->validate(new PresenceOf(
		   array(
				"field"   => "content",
				"message" => "No ha ingresado ningún contenido para el correo, por favor verifique la información"
		)));
		
		if ($this->validationHasFailed() == true) {
			return false;
		}
	}
}