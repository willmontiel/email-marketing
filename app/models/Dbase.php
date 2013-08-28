<?php
/**
 * La clase Modelbase hereda las funciones:
 * beforeUpdate -> Se ejecuta antes de actualizar un objeto y asigna un valor, en este caso un timestamp para el campo updateon
 * beforeCreate -> Se ejecuta antes de crear un objeto y asigna dos valores, en este caso los respectivo timestamp para createdon y updateon
 * $this->useDynamicUpdate(true); -> Hace que en caso de una edición, solo se actualicen los campos que presentan cambios 
 */
class Dbase extends Modelbase
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
	
}
