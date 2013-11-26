<?php
class MailFieldObj
{
	public $html;
	public $text;
	public $subject;
	
	public function __construct($html, $text, $subject) 
	{
		if (trim($subject) === '') {
			throw new InvalidArgumentException('Empty subject');
		}
		else if (trim($html)=== '' && trim($text) === '') {
			throw new InvalidArgumentException('Empty html and subject');
		}
		
		$this->html = $html;
		$this->text = $text;
		$this->subject = $subject;
		
	}
	
	public function getCustomFields()
	{
		$allFields = $this->html . $this->text . $this->subject;
		preg_match_all('/%%([a-zA-Z0-9_\-]*)%%/', $allFields, $arrayFields);
		
		if ($arrayFields === false) {
			throw new InvalidArgumentException('Error returned by Preg_match_all, invalid values');
		}
		
		list($this->fields, $fields) = $arrayFields;
		$f = array_unique($fields);
		$customFields = implode(", ", $f);
		
		return strtolower($customFields);
	}
	
	public function processCustomFields($fieldValuesArray)
	{
		if ($fieldValuesArray == null || !is_array($fieldValuesArray)) {
			throw new InvalidArgumentException('Error processCustomFields received a not valid array');
		}
		
		$f = array('%%EMAIL%%', '%%NOMBRE%%', '%%APELLIDO%%');
		$find = array_merge($f, $this->fields);
		
		$replace = array($fieldValuesArray['email'], $fieldValuesArray['name'], $fieldValuesArray['lastName']);
		
		foreach ($fieldValuesArray as $value) {
			if (trim($value)=== '') {
				$replace[] = " ";
			}
			else {
				$replace[] = $value;
			}
		}
		
		$newHtml = str_ireplace($find, $replace, $this->html);
		$newText = str_ireplace($find, $replace, $this->text);
		$newSubject = str_ireplace($find, $replace, $this->subject);
		
		$content = array(
			'html' => $newHtml,
			'text' => $newText,
			'subject' => $newSubject
		);
		
		return $content;
	}
}
//
//$html = "";
//$text = "";
//$subject = "";
//
//$x = new MailFieldObj($html, $text, $subject);
