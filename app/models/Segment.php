<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Segment extends \Phalcon\Mvc\Model
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
				"message" => "Debes ingresar un nombre para el segmento"
            )
        ));
	}
	
	public function beforeCreate()
    {
        $this->createdon = time();
		if ($this->description == null) {
			$this->description = "Sin descripción";
		}
    }
}
