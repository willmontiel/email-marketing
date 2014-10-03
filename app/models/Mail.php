<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Email;
class Mail extends Modelbase
{
	public $idMail;
	
	public function initialize()
	{
		$this->hasMany("idMail", "Mxc", "idMail");
		$this->hasMany("idMail", "Attachment", "idMail");
		$this->hasMany("idMail", "Mxa", "idMail");
		$this->hasMany("idMail", "Mail", "idMail");
		$this->hasOne("idMail", "Mailcontent", "idMail");
		$this->hasOne("idMail", "Mailschedule", "idMail");
		$this->hasMany("idMail", "Mxl", "idMail");
		$this->hasMany("idMail", "Mxcxl", "idMail");
		$this->hasMany("idMail", "Statdbase", "idMail");
		$this->hasMany("idMail", "Statcontactlist", "idMail");
		
		$this->useDynamicUpdate(true);
	}
	
//	public function validation()
//    {
//		$this->validate(new PresenceOf(
//		   array(
//				"field"   => "name",
//				"message" => "No ha ingresado un nombre para el correo, por favor verifique la información"
//		)));
//		
//		$this->validate(new PresenceOf(
//		   array(
//				"field"   => "subject",
//				"message" => "No ha ingresado un asunto para el correo, por favor verifique la información"
//		)));
//		
//		$this->validate(new PresenceOf(
//		   array(
//				"field"   => "fromName",
//				"message" => "Debe ingresar el nombre del remitente, por favor verifique la información"
//		)));
//		
//		$this->validate(new PresenceOf(
//		   array(
//				"field"   => "fromEmail",
//				"message" => "Debe ingresar la dirección de correo del remitente, por favor verifique la información"
//		)));
//
//		$this->validate(new Email(
//			   array(
//					"field" => "fromEmail",
//					"message" => "La direccion de correo electronico no es valida, por favor verifique la información"
//		)));
		
//		$this->validate(new Email(
//			   array(
//					"field" => "replyTo",
//					"message" => "La dirección ingresada en 'Responser a:' no es válida por favor verifique la información"
//		)));
		
//		if ($this->validationHasFailed() == true) {
//			return false;
//		}
//	}
	
	public function incrementUniqueOpens()
	{
		$this->uniqueOpens += 1;
	}
	
	public function incrementClicks()
	{
		$this->clicks += 1;
	}
	
	public function incrementBounced()
	{
		$this->bounced += 1;
	}
	
	public function incrementSpam()
	{
		$this->spam += 1;
	}
	
	public function incrementUnsubscribed()
	{
		$this->unsubscribed += 1;
	}
}