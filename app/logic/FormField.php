<?php

/*
 * Esta clase tiene como proposito el de insertar los links que llevaran
 * a los formularios de actualizacion para cada contacto.
 * 
 * Los metodos se encargan de transformar la identificacion del formulario que 
 * viene de la creacion del contenido, en el link para redirigir al contacto
 */

class FormField {
	
	public $mail;
	public $forms;
	public $urlManager;
	public $ids;
	
	function __construct(Mail $mail) {
		$this->log = Phalcon\DI::getDefault()->get('logger');
		$this->mail = $mail;
		$this->forms = true;
		$this->urlManager = Phalcon\DI::getDefault()->get('urlManager');
	}
	
	public function formsAvailable()
	{
		return $this->forms;
	}
	
	public function prepareUpdatingForms($html)
	{
		preg_match_all('/%%FORM_([a-zA-Z0-9_\-]*)%%/', $html, $arrayForms);
		
		if (count($arrayForms[0]) == 0) {
			$this->forms = false;
			return $html;
		}
		
		list($allforms, $allids) = $arrayForms;
		
		$this->ids = array_unique($allids);
		
		$search = $replace = array();
		
		foreach($this->ids as $id) {
			$search[] = '%%FORM_' . $id . '%%';
			$replace[] = '$$$_updating_form_' . $id . '_$$$';
		}

		$html_forms = str_ireplace($search, $replace, $html);
		
		return $html_forms;
	}
	
	public function processUpdatingForms($html, $contact)
	{
		$linkdecoder = new \EmailMarketing\General\Links\ParametersEncoder();
		$linkdecoder->setBaseUri($this->urlManager->getBaseUri(true));
		
		foreach($this->ids as $id) {
			$parameters = array(1, $id, $contact['idContact'], $this->mail->idMail);
			$url = $linkdecoder->encodeLink('form/update', $parameters);
			
			$search[] = '$$$_updating_form_' . $id . '_$$$';
			$replace[] = $url;
		}

		$html_form_link = str_ireplace($search, $replace, $html);
		
		return $html_form_link;
	}

}
