<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Mailcontent extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail");
	}
	
	public function validation()
    {
		$this->validate(new PresenceOf(
		   array(
				"field"   => "content",
				"message" => "No ha ingresado ningún contenido para el correo, por favor verifique la información"
		)));
		
		$this->validate(new Uniqueness(
				array(
                "field"   => "idMail",
                "message" => "No se pudo guardar la información , por favor verifique e intente de nuevo"
        )));
		
		if ($this->validationHasFailed() == true) {
			return false;
		}
	}
}