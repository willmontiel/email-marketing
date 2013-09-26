<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Segment extends \Phalcon\Mvc\Model
{
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
    }
}
