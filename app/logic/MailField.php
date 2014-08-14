<?php

class MailField
{
	public $html;
	public $text;
	public $subject;
	public $customFields;
	public $idDbases;
	public $idFields;
	public $fieldsInDbase = array();
	
	/**
	 * El constructor recibe el html, el texto plano, el asunto y los identificadores de las bases de datos
	 * para buscar los campos personalizados 
	 * @param string $html
	 * @param string $text
	 * @param string $subject
	 * @param string $idDbases
	 * @throws InvalidArgumentException
	 */
	public function __construct($html, $text, $subject, $idDbases) 
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');
		
		// 1. Validamos que los campos no esten vacíos, esten correctos y asignamos las variables globales
		if (trim($subject) === '') {
			throw new InvalidArgumentException('Empty subject');
		}
		else if (trim($html)=== '' && trim($text) === '') {
			throw new InvalidArgumentException('Empty html and subject');
		}
		else if (trim($idDbases)=== '') {
			throw new InvalidArgumentException('Empty idDbases');
		}
		
		$this->html = $html;
		$this->text = $text;
		$this->subject = $subject;
		$this->idDbases = $idDbases;
	}
	
	/**
	 * Retorna los posibles campos personalizados (todo lo que esté entre %%, Ej: %%NOMBRE%%) 
	 * encontrados en el html, texto plano y el asunto
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function searchCustomFields()
	{
		/*
		 * 1.Buscamos los posibles campos personalizados y los agregamos a un arreglo (Si están repetidos aparecerán 
		 * dos veces)
		 * Ej:
		 * 
		 * "Contenido enviado a %%NOMBRE%% %%APELLIDO%%, %%NOMBRE%%, 
		 * %hshs%%ggs%hshs% JJJ% $ajaa%%$$%%%% %%%%%% %%EMAIL %%EMAIL%%"
		 * 
		 * Array
			(
				[0] => Array
					(
						[0] => %%NOMBRE%%
						[1] => %%APELLIDO%%
						[2] => %%NOMBRE%%
						[3] => %%%%
						[4] => %%%%
						[5] => %%EMAIL%%
					)

				[1] => Array
					(
						[0] => NOMBRE
						[1] => APELLIDO
						[2] => NOMBRE
						[3] =>
						[4] =>
						[5] => EMAIL
					)
			)
		 */
		
		$allFields = $this->html . $this->text . $this->subject;
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $allFields, $arrayFields);
		
		// 2.Si hay error en el preg_match_all se lanzará una excepción
		if ($arrayFields === false) {
			throw new InvalidArgumentException('Error returned by Preg_match_all, invalid values');
		}
		
		//3.Si no se encuentran campos personalizados simplemente se retornará 
		//una cadena de texto igual a 'No Fields'
		if (count($arrayFields[0]) == 0) {
			$this->idFields = 'No Fields';
			return false;
		}
		
//		$this->log->log("Fields: " . print_r($arrayFields, true));
		
		// 4.Dividimos el arreglo que contiene los posibles campos personalizados en 2
		list($cf, $fields) = $arrayFields;
		
		//5.Creamos la variable global $this->customFields
		$this->setFields($cf);
		
		//6.Organizamos los identificadores los campos personalizados
		$this->setIdFields($fields);
		
		//7. Si no hay coincidencias retornamos el mensaje 'No Custom'
		if (count($this->idFields) <= 0) {
			$this->idFields = 'No Custom';
			return false;
		}
	}
	
	private function setFields($fields)
	{
		//1.Tomamos la primera parte(Los que estan entre %%) del arreglo de posibles campos personalizados 
		// y quitamos los repetidos
		$f = array_unique($fields);
		
		//2.Recorremos el arreglo con valores únicos y tomamos los campos que no sean primarios (Nombre, apellido, email)
		// para insertarlos en la variable global fields
		$this->customFields = array();
		foreach ($f as $x) {
			if ($x == '%%EMAIL%%' || $x == '%%NOMBRE%%' || $x == '%%APELLIDO%%' || $x == '%%FECHA_DE_NACIMIENTO%%​') {
			}
			else {
				$this->customFields[] = $x;
			}
		}
	}
	
	private function setIdFields($fields)
	{
		//1.Tomamos la segunda parte(Los que no están entre %%) del arreglo de posibles campos personalizados
		//y quitamos los repetidos
		$customfieldsFound = array_unique($fields);
		
		//2.Buscamos los campos personalizados de la base de datos
		$this->searchCustomFieldsInDbase();
		
		//3.Recorremos el arreglo y comparamos los campos personalizados encontrados de la base de datos para
		//obtener los identificadores
		$search = array('Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú');
		$replace = array('N', 'A', 'E', 'I', 'O', 'U');
		if ($this->fieldsInDbase) {
			foreach ($this->fieldsInDbase as $r) {
				foreach ($customfieldsFound as $c) {
					if (str_replace($search, $replace, strtoupper($r->name)) == str_replace('_', ' ', $c)) {
						$this->idFields[] = $r->idCustomField;
					}
				}
			}
		}
		
//		$this->log->log("Fields4: " . print_r($this->idFields, true));
	}

	
	private function searchCustomFieldsInDbase()
	{
		// 1.Buscamos los campos personalizados en la base de datos
		$phql = "SELECT * FROM Customfield WHERE idDbase IN ({$this->idDbases})";
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$this->fieldsInDbase = $modelsManager->executeQuery($phql);
	}
	
	
	public function getCustomFields()
	{
		if (is_array($this->idFields)) {
			$this->idFields = strtolower(implode(", ", $this->idFields));
		}
		
		return $this->idFields;
	}
	
	public function processCustomFields($contact)
	{
		//1. Validamos que la variable contacto no esté vacía y sea un arreglo
		if ($contact == null || !is_array($contact)) {
			throw new InvalidArgumentException('Error processCustomFields received a not valid array');
		}
		
		//2.Emparejamos los campos primarios 
		$searchPrimaryFields = array('%%EMAIL%%', '%%NOMBRE%%', '%%APELLIDO%%', '%%FECHA_DE_NACIMIENTO%%​');
		$replacePrimaryFields = array($contact['email']['email'], $contact['contact']['name'], $contact['contact']['lastName'], $contact['contact']['birthDate']);
		
		$searchCustomFields = array();
		$replaceCustomFields = array();
		
		//3.Emparejamos los campos personalizados
		foreach ($this->customFields as $cf) {
			foreach ($contact['fields'] as $key => $value) {
				if ($cf == $key) {
					$searchCustomFields[] = $cf;
					$replaceCustomFields[] = (empty($value) ? " " : $value);
				}
			}
		}
		
		//4.Fusionamos los arreglos con campos personalizados y primarios
		$search = array_merge($searchPrimaryFields, $searchCustomFields);
		$replace = array_merge($replacePrimaryFields, $replaceCustomFields);
		
		$this->log->log("Search: " . print_r($search, true));
		$this->log->log("Replace: " . print_r($replace, true));
		
		//5.Utilizamos str_replace para reemplazar los valores del contacto por la marca de campo personalizado
		$newHtml = str_replace($search, $replace, $this->html);
		$newText = str_replace($search, $replace, $this->text);
		$newSubject = str_replace($search, $replace, $this->subject);
		
		//6.Creamos el arreglo a retornar
		$content = array(
			'html' => $newHtml,
			'text' => $newText,
			'subject' => $newSubject
		);
		
		//7.Hacemos unset de los arreglos para liberar la posible memoria utilizada
		unset($newHtml);
		unset($newText);
		unset($newSubject);
		
		return $content;
	}
}
