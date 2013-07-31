<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;
//use Phalcon\Mvc\Model\Validator\Uniqueness;

class Customfield extends \Phalcon\Mvc\Model
{
	public $idBase;
	public $idCustomField;
	
	public function initialize()
    {
    
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
