<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends \Phalcon\Mvc\Model
{
	public $idAccount;

    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
    }
    
    public function validation()
    {
		$this->validate(new PresenceOf(
		   array(
				"field"   => "email",
				"message" => "Por favor ingresa tu dirección de correo electronico"
		)));

		$this->validate(new Email(
			   array(
					"field" => "email",
					"message" => "La direccion de correo electronico no es valida por favor verifica la información"
		)));
		
		$this->validate(new Uniqueness(
				array(
                "field"   => "email",
                "message" => "Parece que esta dirección de correo electronico ya se encuentra registrada"
        )));

		$this->validate(new PresenceOf(
			   array(
					"field" => "firstName",
					"message" => "El campo nombre esta vacío"
		)));

		$this->validate(new PresenceOf(
			   array(
					"field" => "lastName",
					"message" => "El apellido esta vacío"
		)));

		$this->validate(new PresenceOf(
			   array(
					"field" => "password",
					"message" => "Por favor ingresa una contraseña, (minimo 8 caracteres)"
		)));

		$this->validate(new StringLength(
			   array(
					"field" => "password",
					"min" => 8,
					"message" => "La contraseña es muy corta, debe ser de 8 a 40 caracteres"
		)));

		$this->validate(new PresenceOf(
			   array(
					"field" => "username",
					"message" => "Por favor ingresa el nombre de usuario, se necesitará para iniciar sesión"
		)));

		$this->validate(new Regex(
				array(
					 'field' => 'username',
					 'pattern' => '/^[a-z\d_]{4,15}$/i',
					 'message' => 'EL nombre de usuario no de tener espacios ni caracteres especiales'

		 )));
		
		$this->validate(new Uniqueness(
				array(
                "field"   => "username",
                "message" => "Parece que el nombre de usuario ya existe, por favor verifica la informacion"
        )));

		if ($this->validationHasFailed() == true) {
			return false;
		}
			
	}
	
		
}
	