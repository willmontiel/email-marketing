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
	
	public function validation()
    {
		$this->validate(new PresenceOf(
		   array(
				"field"   => "name",
				"message" => "No ha ingresado un nombre para el correo, por favor verifique la información"
		)));
		
		$this->validate(new PresenceOf(
		   array(
				"field"   => "subject",
				"message" => "No ha ingresado un asunto para el correo, por favor verifique la información"
		)));
		
		$this->validate(new PresenceOf(
		   array(
				"field"   => "fromName",
				"message" => "Debe ingresar el nombre del remitente, por favor verifique la información"
		)));
		
		$this->validate(new PresenceOf(
		   array(
				"field"   => "fromEmail",
				"message" => "Debe ingresar la dirección de correo del remitente, por favor verifique la información"
		)));

		$this->validate(new Email(
			   array(
					"field" => "fromEmail",
					"message" => "La direccion de correo electronico no es valida, por favor verifique la información"
		)));
		
		if ($this->validationHasFailed() == true) {
			return false;
		}
	}
}