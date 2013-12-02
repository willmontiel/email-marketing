<?php
class MailField
{
	public $html;
	public $text;
	public $subject;
	public $fields;
	public $idDbases;
	
	public function __construct($html, $text, $subject, $idDbases) 
	{
		$this->log = Phalcon\DI::getDefault()->get('logger');
		
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
	
	public function getCustomFields()
	{
		$phql = "SELECT * FROM Customfield WHERE idDbase IN (" . $this->idDbases . ")";
		$modelsManager = Phalcon\DI::getDefault()->get('modelsManager');
		$result = $modelsManager->executeQuery($phql);
		
		$allFields = $this->html . $this->text . $this->subject;
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $allFields, $arrayFields);
		
		if ($arrayFields === false) {
			throw new InvalidArgumentException('Error returned by Preg_match_all, invalid values');
		}
		
//		$this->log->log("Fields: " . print_r($arrayFields, true));
		list($cf, $fields) = $arrayFields;
		$f = array_unique($cf);
		$this->fields = array();
		foreach ($f as $x) {
			if ($x == '%%EMAIL%%' || $x == '%%NOMBRE%%' || $x == '%%APELLIDO%%') {
				
			}
			else {
				$this->fields[] = $x;
			}
		}
		$customfieldsFound = array_unique($fields);
//		$this->log->log("Fields2: " . print_r($customfieldsFound, true));
	
		$ids = array();
		if ($result) {
			foreach ($result as $r) {
				foreach ($customfieldsFound as $c) {
					if (strtoupper($r->name) == $c) {
						$ids[] = $r->idCustomField;
					}
				}
			}
		}
//		$this->log->log("Fields4: " . print_r($ids, true));
		if (count($ids) <= 0) {
			return false;
		}
		
		$custom = strtolower(implode(", ", $ids));
		return $custom;
	}
	
	public function processCustomFields($contact)
	{
		if ($contact == null || !is_array($contact)) {
			throw new InvalidArgumentException('Error processCustomFields received a not valid array');
		}
		
		$f = array('%%EMAIL%%', '%%NOMBRE%%', '%%APELLIDO%%');
		$find = array_merge($f, $this->fields);
		
//		$this->log->log("Custom: " . print_r($find, true));
		
		$replace = array($contact['email']['email'], $contact['contact']['name'], $contact['contact']['lastName']);
		
		foreach ($contact['fields'] as $key => $value) {
			if (trim($value)=== '' || empty($value)) {
				$replace[] = " ";
			}
			else {
				$replace[] = $value;
			}
		}
//		$this->log->log("Replace: " . print_r($replace, true));
//		$this->log->log("Html antes del susodicho: " . $this->html);
		$newHtml = str_replace($find, $replace, $this->html);
		$newText = str_replace($find, $replace, $this->text);
		$newSubject = str_replace($find, $replace, $this->subject);
		
		$content = array(
			'html' => $newHtml,
			'text' => $newText,
			'subject' => $newSubject
		);
		
		return $content;
	}
}
