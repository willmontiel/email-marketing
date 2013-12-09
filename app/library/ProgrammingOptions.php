<?php
class ProgrammingOptions
{	
	public static function getOptions($mail) {
		
		$array = array();
		
		switch ($mail->status) {
			case 'Scheduled':
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
				$array[] = self::getObject("Sin acciones");
				break;
		}
		return $array;
	}
	
	protected static function getObject($option) {
		
		$object = new stdClass();
		
		switch ($option) {
			case 'Reanudar':
				$object->text = "Reanudar";
				$object->url = "programmingmail/play/";
				$object->icon = "icon-signin";
				break;
			case 'Detener':
				$object->text = "Detener";
				$object->url = "programmingmail/stop/";
				$object->icon = "icon-signin";
				break;
			case 'Cancelar':
				$object->text = "Cancelar";
				$object->url = "programmingmail/cancel/";
				$object->icon = "icon-signin";
				break;
			default:
				$object->text = "No hay acciones disponibles";
				$object->url = "null";
				$object->icon = "icon-signin";
				break;
		}
		return $object;
	}
}
