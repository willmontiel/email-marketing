<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Customfield extends \Phalcon\Mvc\Model
{
	public $idBase;
	public $idCustomField;
	
	public function initialize()
    {
    
		$this->useDynamicUpdate(true);
		$this->belongsTo("idDbase", "Dbase", "idDbase", array(
            "foreignKey" => true,
        ));
		
    }
	public function beforeValidationOnCreate()
    {
        if (!isset($this->values) || $this->values == NULL) {
			$this->values = "";
        }
		
		if (!isset($this->required) || $this->required == NULL) {
			$this->required = 2;
        }
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