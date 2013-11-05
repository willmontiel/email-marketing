<?php
class SmartWizard 
{	
	protected static function di()
    {
        return \Phalcon\DI\FactoryDefault::getDefault();
    }
	
	public static function getWizard(Mail $mail) 
	{
		$di = self::di();
		
		$dispatcher = $di['dispatcher'];
		$action = $dispatcher->getActionName();
		
		$wizard = array (
			"setup" => array(
				"name" => "Información de correo",
				"url" => "#",
				"wizard" => "breadcrumb-button",
				"icon" => "icon-check"
			),
			"source" => array(
				"name" => "Editar/Crear contenido",
				"url" => "#",
				"wizard" => "breadcrumb-button",
				"icon" => "icon-edit"
			),
			"target" => array(
				"name" => "Seleccionar destinatarios",
				"url" => "#",
				"wizard" => "breadcrumb-button",
				"icon" => "icon-group"
			),
			"schedule" => array(
				"name" => "Programar envío",
				"url" => "#",
				"wizard" => "breadcrumb-button",
				"icon" => "icon-calendar"
			)
		);
		
		return self::createWizard($action, $wizard, $mail);
	}
	
	protected static function createWizard($action, $wizard, $mail)
	{
		switch ($mail->wizardOption) {
			case 'setup':
				switch ($action) {
					case 'setup':
						$wizard['setup']['wizard'] = 'breadcrumb-button blue';
						$wizard['source']['url'] = 'mail/source';
						break;
					case 'source':
						$wizard['source']['wizard'] = 'breadcrumb-button blue';
						$wizard['setup']['url'] = 'mail/setup';
						break;
					case 'editor':
					case 'template':
					case 'html':
					case 'import':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['wizard'] = 'breadcrumb-button blue';
						break;
					default:
						break;
				}
				break;
			
			case 'source':
				switch ($action) {
					case 'setup':
						$wizard['setup']['wizard'] = 'breadcrumb-button blue';
						$wizard['source']['url'] = 'mail/source';
						break;
					case 'editor':
					case 'html':
					case 'plaintext':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['wizard'] = 'breadcrumb-button blue';
						break;
					case 'target':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['wizard'] = 'breadcrumb-button blue';
						break;
				}
				break;
			case 'target':
				switch ($action) {
					case 'setup':
						$wizard['setup']['wizard'] = 'breadcrumb-button blue';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['url'] = 'mail/target';
						break;
					case 'plaintext':
					case 'editor':
					case 'html':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['wizard'] = 'breadcrumb-button blue';
						$wizard['target']['url'] = 'mail/target';
						break;
					case 'target':
					case 'filter':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['wizard'] = 'breadcrumb-button blue';
						break;
					case 'schedule':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['url'] = 'mail/target';
						$wizard['schedule']['wizard'] = 'breadcrumb-button blue';
						break;
				}
				break;
			case 'schedule':
				switch ($action) {
					case 'setup':
						$wizard['setup']['wizard'] = 'breadcrumb-button blue';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['url'] = 'mail/target';
						$wizard['schedule']['url'] = 'mail/schedule';
						break;
					case 'plaintext':
					case 'editor':
					case 'html':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['wizard'] = 'breadcrumb-button blue';
						$wizard['target']['url'] = 'mail/target';
						$wizard['schedule']['url'] = 'mail/schedule';
						break;
					case 'target':
					case 'filter':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['wizard'] = 'breadcrumb-button blue';
						$wizard['schedule']['url'] = 'mail/schedule';
						break;
					case 'schedule':
					case 'preview':
						$wizard['setup']['url'] = 'mail/setup';
						$wizard['source']['url'] = 'mail/source';
						$wizard['target']['url'] = 'mail/target';
						$wizard['schedule']['wizard'] = 'breadcrumb-button blue';
						break;
				}
				break;
		}
		
		return $wizard;
	}
}
