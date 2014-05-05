<?php
class ProgrammingOptions
{	
	public static function getOptions($mail) {
		
		$array = array();
		
		switch ($mail->status) {
			case 'Scheduled':
				$array[] = self::getObject("Cancelar");
				$array[] = self::getObject("Pausar");
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
				$object->url = "scheduledmail/play/";
				$object->icon = "icon-play";
				break;
			case 'Detener':
				$object->text = "Detener";
				$object->url = "scheduledmail/stop/";
				$object->icon = "icon-pause";
				break;
			case 'Cancelar':
				$object->text = "Cancelar";
				$object->url = "scheduledmail/cancel/";
				$object->icon = "icon-remove";
				break;
			case 'Pausar':
				$object->text = "Pausar";
				$object->url = "scheduledmail/stop/";
				$object->icon = "icon-pause";
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
