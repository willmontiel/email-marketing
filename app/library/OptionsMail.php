<?php
class OptionsMail 
{	
	public static function getOptions($mail) {
		
		$array = array();
		
		switch ($mail->status) {
			case 'Draft':
				if ($mail->pdf == 1) {
					$array[] = self::getObject("Editar-pdf");
				}
				else {
					$array[] = self::getObject("Editar");
				}
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
				$object->text = "Reanudar el envío de este correo";
				$object->url = "mail/play/";
				$object->icon = "glyphicon glyphicon-play";
				break;
			case 'Editar-pdf':
				$object->text = "Editar este correo";
				$object->url = "pdfmail/compose/";
				$object->icon = "glyphicon glyphicon-edit";
				break;
			case 'Editar':
				$object->text = "Editar este correo";
				$object->url = "mail/compose/";
				$object->icon = "glyphicon glyphicon-edit";
				break;
			case 'Detener':
				$object->text = "Detener el envío de este correo";
				$object->url = "mail/stop/index/";
				$object->icon = "glyphicon glyphicon-stop";
				break;
			case 'Cancelar':
				$object->text = "Cancelar el envío de este correo";
				$object->url = "mail/cancel/";
				$object->icon = "glyphicon glyphicon-warning-sign";
				break;
		}
		return $object;
	}
}
