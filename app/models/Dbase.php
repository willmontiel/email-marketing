<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class Dbase extends Modelbase
{

    public $idAccount;
	public $idDbase;


	public function initialize()
    {
        $this->hasMany("idDbase", "Customfield", "idDbase", array('alias' => 'CustomFields'));
		
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));

        $this->hasMany("idDbase", "Contact", "idDbase", array('alias' => 'Contacts'));
		$this->hasMany("idDbase", "Contactlist", "idDbase");
		$this->hasMany("idDbase", "Segment", "idDbase");
		$this->hasMany("idDbase", "Statdbase", "idDbase");
    }
    
	public function validate() 
	{
		$this->validate(new PresenceOf(
            array(
                "field"   => "name",
				"message" => "Debe ingresar un nombre para la base de datos"
            )
        ));
		
		$this->validate(new PresenceOf(
            array(
                "field"   => "description",
				"message" => "Debe ingresar una descripción para la base de datos"
            )
        ));
		
		$this->validate(new PresenceOf(
            array(
                "field"   => "Cdescription",
				"message" => "Debe ingresar una descripción de contactos para la base de datos"
            )
        ));
		
		if ($this->validationHasFailed() == true) {
			return false;
		}
	}
	
	public function updateCountersInDbase()
	{
		$db = Phalcon\DI::getDefault()->get('db');
		
		$sql = "CALL update_counters_db($this->idDbase)";

		$db->execute($sql);
	}
}