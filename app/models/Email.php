<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Email extends \Phalcon\Mvc\Model
{
	public  $idDomain;
	public function inicializate()
	{
		$this->belongsTo("idDomain", "Domain", "idDomain", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
		
	}

		public function validation()
	{
		
		$this->validate(new PresenceOf(
		   array(
				"field"   => "email",
				"message" => "Debes ingresar una dirección de correo electronico"
		)));

		$this->validate(new Email(
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

    }

    public function beforeUpdate()
    {
        $this->updatedon = time();
    }
	
}

