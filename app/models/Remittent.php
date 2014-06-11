<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Remittent extends \Phalcon\Mvc\Model
{
	public $idAccount;
	
    public function initialize()
    {
		$this->belongsTo("idAccount", "Account", "idAccount",
			array("foreignKey" => true)
		);
	}
	
	 public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "email",
				"message" => "Debe ingresar al menos la direccion de un remitente válido"
            )
        ));
		
		if ($this->validationHasFailed() == true) {
			return false;
        }
	}
}
