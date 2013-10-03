<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\StringLength;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\Uniqueness;
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class User extends Modelbase
{
	public $idAccount;

    public function initialize()
    {
        $this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		
    }
    
    public function validation()
    {
		$this->validate(new PresenceOf(
		   array(
				"field"   => "email",
				"message" => "No ha ingresado una dirección de correo electronico, por favor verifique la información"
		)));

		$this->validate(new Email(
			   array(
					"field" => "email",
					"message" => "La direccion de correo electronico no es valida por favor verifique la información"
		)));
		
		$this->validate(new Uniqueness(
				array(
                "field"   => "email",
                "message" => "La dirección de correo electrónico ya se enceuntra registrada, por favor verifique la información"
        )));

		$this->validate(new PresenceOf(
			   array(
					"field" => "firstName",
					"message" => "El campo nombre esta vacío"
		)));

		$this->validate(new PresenceOf(
			   array(
					"field" => "lastName",
					"message" => "El campo apellido esta vacío"
		)));

		$this->validate(new PresenceOf(
			   array(
					"field" => "password",
					"message" => "No ha ingresado una contraseña, (minimo 8 caracteres)"
		)));

		$this->validate(new StringLength(
			   array(
					"field" => "password",
					"min" => 8,
					"message" => "La contraseña es muy corta, debe estar entre 8 y 40 caracteres"
		)));

		$this->validate(new PresenceOf(
			   array(
					"field" => "username",
					"message" => "Por favor ingrese el nombre de usuario, se necesitará para iniciar sesión"
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
                "message" => "Nombre de usuario invalido, verifique la información"
        )));

		if ($this->validationHasFailed() == true) {
			return false;
		}
	}		
}
	