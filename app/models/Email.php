<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Email as EmailV;

class Email extends \Phalcon\Mvc\Model
{
	public  $idDomain;

	public function initialize()
	{
		$this->belongsTo("idDomain", "Domain", "idDomain", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		
		$this->useDynamicUpdate(true);
		
		$this->createdon = 0;
		$this->updatedon = 0;
		
	}

	public function validation()
	{
		
		$this->validate(new PresenceOf(
		   array(
				"field"   => "email",
				"message" => "Debes ingresar una dirección de correo electronico"
		)));

		$this->validate(new EmailV(
			   array(
					"field" => "email",
					"message" => "La direccion de correo electronico no es valida por favor verifica la información"
		)));
		if ($this->validationHasFailed() == true) {
			return false;
		}
	}
	
	public function beforeCreate()
    {
        $this->createdon = time();
        $this->updatedon = time();
    }

    public function beforeUpdate()
    {
        $this->updatedon = time();
    }
	
}

