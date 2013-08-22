<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Uniqueness;
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class Account extends Modelbase
{
    public $idAccount;

    public function initialize()
    {
        $this->hasMany("idAccount", "User", "idAccount");
		$this->hasMany("idAccount", "Dbase", "idAccount", array('alias' => 'Dbases'));
		$this->useDynamicUpdate(true);
    }
    
    public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "companyName",
				"message" => "Debes ingresar un nombre para la cuenta"
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
    