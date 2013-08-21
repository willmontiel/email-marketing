<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
class Contactlist extends \Phalcon\Mvc\Model 
{
	public $idDbase;
	public function initialize()
	{
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		
	}
	
	public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "name",
				"message" => "Debes ingresar un nombre para la lista"
            )
        ));
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
