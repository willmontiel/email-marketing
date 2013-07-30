<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
//use Phalcon\Mvc\Model\Validator\Uniqueness;

class CustomField extends \Phalcon\Mvc\Model
{
	public $idBase;
	public $idCustomField;
	
	public function initialize()
    {
        $this->belongsTo("idCustomField", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
    }

	public function validation()
    {
		$this->validate(new PresenceOf(
            array(
                "field"   => "name",
				"message" => "Debes asignar un nombre al campo personalizado"
            )
        ));
		
		if ($this->validationHasFailed() == true) {
			return false;
        }
		
	}
}