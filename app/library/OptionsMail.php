<?php
class OptionsMail 
{	
	public static function getOptions($mail) {
		
		$array = array();
		
		switch ($mail->status) {
			case 'Draft':
				$array[] = self::getObject("Editar");
				break;
			case 'Scheduled':
				$array[] = self::getObject("Editar");
				$array[] = self::getObject("Cancelar");
				break;
			case 'Sending':
				$array[] = self::getObject("Detener");
				$array[] = self::getObject("Cancelar");
				break;
			case 'Paused':
				$array[] = self::getObject("Reanudar");
				$array[] = self::getObject("Cancelar");
				break;
			default :
				break;
		}
		return $array;
	}
	
	protected static function getObject($option) {
		
		$object = new stdClass();
		
		switch ($option) {
			case 'Reanudar':
				$object->text = "Reanudar";
				$object->url = "mail/play/";
				$object->urlProgramming = "programmingmail/play/";
				$object->icon = "icon-signin";
				break;
			case 'Editar':
				$object->text = "Editar";
				$object->url = "mail/setup/";
				$object->urlProgramming = "mail/setup/";
				$object->icon = "icon-pencil";
				break;
			case 'Detener':
				$object->text = "Detener";
				$object->url = "mail/stop/";
				$object->urlProgramming = "programmingmail/stop/";
				$object->icon = "icon-signin";
				break;
			case 'Cancelar':
				$object->text = "Cancelar";
				$object->url = "mail/cancel/";
				$object->urlProgramming = "programmingmail/cancel/";
				$object->icon = "icon-signin";
				break;
		}
		return $object;
	}
}
