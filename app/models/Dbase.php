<?php
use Phalcon\Mvc\Model\Validator\PresenceOf,
    Phalcon\Mvc\Model\Validator\StringLength,
    Phalcon\Mvc\Model\Validator\Email,
    Phalcon\Mvc\Model\Validator\Identical;

class Dbase extends \Phalcon\Mvc\Model
{

    public $idAccount;
	public $idDbase;


	public function initialize()
    {
        $this->hasMany("idDbase", "Customfield", "idDbase", array('alias' => 'CustomFields'));
		
		$this->belongsTo("idAccount", "Account", "idAccount", array(
            "foreignKey" => true,
        ));

        $this->hasMany("idDbase", "Contact", "idDbase", array('alias' => 'Contacts'));
		$this->hasMany("idDbase", "Contactlist", "idDbase");
		
		$this->useDynamicUpdate(true);
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
