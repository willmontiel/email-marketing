<?php
class OptionsMail 
{	
	public static function getOptions($status) {
		
		$array = array();
				
		switch ($status) {
			case 'Draft':
				$array[] = self::getObject("Enviar");
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
				$array[] = self::getObject("Enviar");
				$array[] = self::getObject("Cancelar");
				break;
			default :
				break;
		}
		Phalcon\DI::getDefault()->get('logger')->log(print_r($array, true));
		return $array;
	}
	
	protected static function getObject($option) {
		
		$object = new stdClass();
		
		switch ($option) {
			case 'Enviar':
				$object->text = "Enviar";
				$object->url = "mail/#/";
				$object->icon = "icon-signin";
				break;
			case 'Editar':
				$object->text = "Editar";
				$object->url = "mail/#/";
				$object->icon = "icon-pencil";
				break;
			case 'Detener':
				$object->text = "Detener";
				$object->url = "mail/#/";
				$object->icon = "icon-signin";
				break;
			case 'Cancelar':
				$object->text = "Cancelar";
				$object->url = "mail/#/";
				$object->icon = "icon-signin";
				break;
		}
		return $object;
	}
}
