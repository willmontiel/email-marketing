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
	public $cleanFields = array();
	
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
		$array = $arrayFields[0];
		if (count($array) == 0) {
			return 'No Fields';
		}
		
//		$this->log->log("Fields: " . print_r($arrayFields, true));
		
		// 4.Dividimos el arreglo que contiene los posibles campos personalizados en 2
		list($cf, $fields) = $arrayFields;
		
		//5.Creamos la variable global $this->customFields
		$this->setFields($cf);
		
		//6.Organizamos los identificadores los campos personalizados
		$this->setIdFields();
		
		//7. Si no hay coincidencias retornamos el mensaje 'No Custom'
		if (count($this->idFields) <= 0) {
			return 'No Custom';
		}
		
		$this->idFields = implode(", ", $this->idFields);
		
		return 'Fields';
	}
	
	private function setFields($fields)
	{
		//1.Tomamos la primera parte(Los que estan entre %%) del arreglo de posibles campos personalizados 
		// y quitamos los repetidos
		$this->cleanFields = array_unique($fields);
		//2.Recorremos el arreglo con valores únicos y tomamos los campos que no sean primarios (Nombre, apellido, email)
		// para insertarlos en la variable global fields
		$this->customFields = array();
		foreach ($this->cleanFields as $x) {
			if ($x == '%%EMAIL%%' || $x == '%%NOMBRE%%' || $x == '%%APELLIDO%%' || $x == '%%FECHA_DE_NACIMIENTO%%') {
			}
			else {
				$this->customFields[] = $x;
			}
		}
	}
	
	private function setIdFields()
	{
		//1.Buscamos los campos personalizados de la base de datos
		$this->searchCustomFieldsInDbase();
		
		//2.Recorremos el arreglo y comparamos los campos personalizados encontrados de la base de datos para
		//obtener los identificadores.
		$search =  array('Ñ', 'ñ', 'Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú');
		$replace = array('N', 'n', 'A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u');
		
		$this->idFields = array();
		$this->fieldsDB = array();
		
		if ($this->fieldsInDbase) {
			
			$ff = array();
			foreach ($this->fieldsInDbase as $r) {
				foreach ($this->customFields as $c) {
					$fieldDB = str_replace($search, $replace, $r->name);
					$fieldDB = strtoupper($fieldDB);
					$fieldHtml = str_replace(array('_', '%%'), array(' ', ''), $c);
					if ($fieldDB == $fieldHtml) {
						$this->idFields[] = $r->idCustomField;
						$ff[$r->idCustomField] = $c;
					}
				}
			}
			
			$this->customFields = $ff;
		}
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
		return $this->idFields;
	}
	
	public function processCustomFields($contact)
	{
		//1. Validamos que la variable contacto no esté vacía y sea un arreglo
		if ($contact == null || !is_array($contact)) {
			throw new InvalidArgumentException('Error processCustomFields received a not valid array');
		}
		
		//2.Emparejamos los campos primarios 
		$searchPrimaryFields = array('%%EMAIL%%', '%%NOMBRE%%', '%%APELLIDO%%', '%%FECHA_DE_NACIMIENTO%%');
		$replacePrimaryFields = array($contact['email']['email'], $contact['contact']['name'], $contact['contact']['lastName'], $contact['contact']['birthDate']);
		
		$searchCustomFields = array();
		$replaceCustomFields = array();
		
		//3.Emparejamos los campos personalizados
		foreach ($this->customFields as $idh => $cf) {
			foreach ($contact['fields'] as $idc => $value) {
				if ($idh == $idc) {
					$searchCustomFields[] = $cf;
					$replaceCustomFields[] = (empty($value) ? " " : $value);
				}
			}
		}
		
		//4.Fusionamos los arreglos con campos personalizados y primarios
		$search = array_merge($searchPrimaryFields, $searchCustomFields);
		$replace = array_merge($replacePrimaryFields, $replaceCustomFields);
		
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
	
	public function cleanMarks($c)
	{
		$replace = array();
		
		for ($i = 0; $i < count($this->cleanFields); $i++) {
			$replace[] = " ";
		}
		
		$newHtml = str_replace($this->cleanFields, $replace, $c['html']);
		$newText = str_replace($this->cleanFields, $replace, $c['text']);
		$newSubject = str_replace($this->cleanFields, $replace, $c['subject']);
		
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
