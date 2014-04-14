<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Email as EmailV;
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class Email extends Modelbase
{
	public $idEmail;
	public $idAccount;
	public $idDomain;

	public function initialize()
	{
		$this->HasOne("idEmail", "Blockedemail", "idEmail");
		
		$this->belongsTo("idDomain", "Domain", "idDomain", array(
            "foreignKey" => true,
        ));
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		$this->hasMany("idEmail", "Contact", "idEmail", array('alias' => 'Contacts'));
		
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

}

