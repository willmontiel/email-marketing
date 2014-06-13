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
			case 'Pending':
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
				$object->icon = "glyphicon glyphicon-play";
				break;
			case 'Editar':
				$object->text = "Editar";
				$object->url = "mail/compose/";
				$object->icon = "glyphicon glyphicon-edit";
				break;
			case 'Detener':
				$object->text = "Detener";
				$object->url = "mail/stop/index/";
				$object->icon = "glyphicon glyphicon-stop";
				break;
			case 'Cancelar':
				$object->text = "Cancelar";
				$object->url = "mail/cancel/";
				$object->icon = "glyphicon glyphicon-exclamation-sign";
				break;
		}
		return $object;
	}
}
