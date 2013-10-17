<?php
use Phalcon\Mvc\Model\Validator\PresenceOf;

class Mailcontent extends \Phalcon\Mvc\Model
{
	public function initialize()
	{
		$this->belongsTo("idMail", "Mail", "idMail");
		
		$this->useDynamicUpdate(true);
	}
	
	public function getMessages()
    {
        $messages = array();
        foreach (parent::getMessages() as $message) {
            switch ($message->getType()) {
                case 'PresenceOf':
                    $messages[] = 'No ha ingresado ningún contenido para el correo, por favor verifique la información';
                    break;
            }
        }
        return $messages;
    }
}