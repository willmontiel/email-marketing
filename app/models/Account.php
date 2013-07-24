<?php
use Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\StringLength,
    Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Identical;
use Phalcon\Mvc\Controller;

class Account extends \Phalcon\Mvc\Model
{
    
    public function validation()
    {

//        $this->validate( new PresenceOf(
//            array(
//                "field"  => "companyName",
//                "message" => "Oye! Debes ingresar un nombre para tu cuenta"
//            )
//        ));
//        
//        

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

        if ($this->validationHasFailed() == true) {
        return false;
        }
    }
  
    public function getMessages()
    {
        $messages = array();
        foreach (parent::getMessages() as $message) {
            switch ($message->getType()) {
                case 'InvalidCreateAttempt':
                    $messages[] = 'The record cannot be created because it already exists';
                    break;
                case 'InvalidUpdateAttempt':
                    $messages[] = 'The record cannot be updated because it already exists';
                    break;
                case 'PresenceOf':
                    if ($message->getField() == 'companyName') {
                        $messages[] = 'Oye! Debes ingresar un nombre para tu cuenta';
                    }
                    else {
                        $messages[] = $message;
                    }
                    break;
            }
        }
        return $messages;
    }
           
}
    