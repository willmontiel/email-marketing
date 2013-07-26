<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Account extends \Phalcon\Mvc\Model
{
    public $idAccount;

    public function initialize()
    {
        $this->hasMany("idAccount", "User", "idAccount");
		$this->hasMany("idAccount", "Dbases", "idAccount");
    }
    
    public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "companyName",
				"message" => "Oye! Debes ingresar un nombre para la cuenta"
            )
        ));
		
		$this->validate(new Uniqueness(
				array(
                "field"   => "companyName",
                "message" => "Parece que el nombre de la cuenta ya existe, por favor verifica la información"
        )));
		
        $this->validate(new PresenceOf(
            array(
                "field"   => "fileSpace",
                "message" => "Debes indicar la cuota de espacio para archivos"
            )
        ));
        
        $this->validate(new PresenceOf(
            array(
                "field"   => "messageQuota",
                "message" => "Debes indicar el total de cuota de mensajes"
            )
        ));

        if ($this->validationHasFailed() == true) {
			return false;
        }
    }
           
}
    