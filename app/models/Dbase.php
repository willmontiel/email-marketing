<?php
use Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\StringLength,
    Phalcon\Mvc\Model\Validator\Email,
    Phalcon\Mvc\Model\Validator\Identical,
    Phalcon\Mvc\Controller;

class Dbase extends \Phalcon\Mvc\Model
{

    public $idAccount;
	public $idDbase;


	public function initialize()
    {
        $this->hasMany("idDbase", "Customfield", "idDbase");
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));
		$this->useDynamicUpdate(true);
    }
//    public function validation()
//    {
//
//        $this->validate( new PresenceOf(
//            array(
//                "field"  => "name",
//                "message" => array("Oye! Debes ingresar un nuevo nombre para tu Base de Datos")
//            )
//        ));
//        return $this->validationHasFailed() != true;
//    }
    
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
                    if ($message->getField() == 'name') {
                        $messages[] = 'Oye! Debes ingresar un nuevo nombre para tu Base de Datos';
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
?>
