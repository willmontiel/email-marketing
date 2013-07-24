<?php
use Phalcon\Validation\Validator\PresenceOf,
    Phalcon\Validation\Validator\StringLength,
    Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Mvc\Controller;

class Account extends \Phalcon\Mvc\Model
{
    public function validation()
    {

        $this->validate( new PresenceOf(
            array(
                "field"  => "companyName",
                "message" => array("Oye! Debes ingresar un nombre para tu cuenta")
            )
        ));
        
        $this->validate(new MaxMinValidator(
            array(
                "field"  => "companyName",
                "min" => 5,
                "max" => 50,
                "message" => array("El nombre de tu cuenta debe estar entre 5 y 50 caracteres")
            )
        ));

        $this->validate(new PresenceOf(
            array(
                "field"   => "email",
                "message" => "Por favor ingresa tu dirección de correo electronico"
            )
        ));
        
        $this->validate(new Email(
            array(
                "field"   => "email",
                "message" => "La direccion de correo electronico no es valida por favor verifica la información"
            )
        ));
        
        $this->validate(new PresenceOf(
            array(
                "field"   => "fileSpace",
                "message" => "Debes indicar la cuota de espacio para archivos"
            )
        ));
        
        $this->validate(new PresenceOf(
            array(
                "field"   => "messageQuota",
                "message" => "Debes indicar el total de cuenta de mensajes"
            )
        ));

        return $this->validationHasFailed() != true;
    }
  
            
           
}
    