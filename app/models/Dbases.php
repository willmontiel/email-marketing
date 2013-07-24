<?php

class Dbases extends \Phalcon\Mvc\Model
{
    public function validation()
    {

        $this->validate( new PresenceOf(
            array(
                "field"  => "name",
                "message" => array("Oye! Debes ingresar un nombre para tu cuenta")
            )
        ));
        return $this->validationHasFailed() != true;
    }
}
?>
